import numpy as np
import pickle  # Pour sauvegarder et charger le vectorizer
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Embedding, LSTM, Dense, Dropout
from tensorflow.keras.preprocessing.sequence import pad_sequences
from sklearn.model_selection import train_test_split
from sklearn.utils.class_weight import compute_class_weight
from sklearn.metrics import classification_report
from main import texts, labels, vectorizer  # Assurez-vous que ces objets sont bien définis dans main.py

# 1. Vectorisation des textes avec TF-IDF
X = vectorizer.transform(texts).toarray()  # Transformer les textes en vecteurs TF-IDF
y = np.array(labels)  # Convertir les étiquettes en tableau numpy

# 2. Sauvegarder le vectorizer pour une utilisation future
with open('vectorizer.pkl', 'wb') as f:
    pickle.dump(vectorizer, f)
print("Vectorizer sauvegardé dans vectorizer.pkl.")

# 3. Padding des séquences pour uniformiser la taille des entrées
max_sequence_length = 100  # Longueur maximale des séquences (ajustez si nécessaire)
X_pad = pad_sequences(X, padding='post', maxlen=max_sequence_length)

# 4. Séparation des données en ensembles d'entraînement et de validation
X_train, X_val, y_train, y_val = train_test_split(X_pad, y, test_size=0.2, random_state=42)

# 5. Calcul des poids de classe pour gérer un éventuel déséquilibre des classes
class_weights = compute_class_weight('balanced', classes=np.unique(y), y=y)
class_weights = dict(enumerate(class_weights))  # Conversion en dictionnaire pour Keras

# 6. Création du modèle LSTM
model = Sequential()
model.add(Embedding(input_dim=len(vectorizer.get_feature_names_out()), output_dim=128, input_length=max_sequence_length))
model.add(LSTM(256, return_sequences=True))  # Première couche LSTM avec 256 unités
model.add(LSTM(128))  # Deuxième couche LSTM avec 128 unités
model.add(Dropout(0.5))  # Dropout pour éviter le sur-apprentissage
model.add(Dense(1, activation='sigmoid'))  # Couche de sortie pour classification binaire

# 7. Compilation du modèle
model.compile(loss='binary_crossentropy', optimizer='adam', metrics=['accuracy'])

# 8. Entraînement du modèle
history = model.fit(
    X_train, y_train,
    epochs=10,  # Nombre d'époques (ajustez selon vos besoins)
    batch_size=32,  # Taille du batch
    validation_data=(X_val, y_val),
    class_weight=class_weights  # Gestion du déséquilibre des classes
)

# 9. Évaluation du modèle sur les données de validation
loss, accuracy = model.evaluate(X_val, y_val)
print(f"Loss: {loss}, Accuracy: {accuracy}")

# 10. Prédictions sur l'ensemble de validation
y_pred = model.predict(X_val)
y_pred = (y_pred > 0.5).astype(int)  # Conversion des probabilités en classes (ajustez le seuil si nécessaire)

# 11. Affichage du rapport de classification
print(classification_report(y_val, y_pred))

# 12. Sauvegarde du modèle entraîné pour une utilisation future
model.save('hate_speech_model.keras')
print("Modèle sauvegardé dans hate_speech_model.keras.")

# 13. Affichage des statistiques sur les ensembles de données
total_data = len(texts)  # Total des exemples dans le corpus
print(f"Nombre total de données : {total_data}")
print(f"Nombre de données d'entraînement : {len(X_train)}")
print(f"Nombre de données de validation : {len(X_val)}")
