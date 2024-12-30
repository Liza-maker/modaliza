from flask import Flask, request, jsonify
import numpy as np
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing.sequence import pad_sequences
from sklearn.feature_extraction.text import TfidfVectorizer  # Utilisez votre vectorizer si nécessaire

# Charger le modèle pré-entraîné
model = load_model('hate_speech_model.keras')

# Charger le vectorizer, soit avec une sauvegarde (si vous en avez), ou en créant un nouveau
vectorizer = TfidfVectorizer()  # A adapter selon le vectorizer que vous avez utilisé

app = Flask(__name__)

# Route pour la prédiction du message
@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()  # Récupère les données envoyées par l'API via POST
    message = data['message']  # Récupère le message

    # Prétraiter le message (vectorisation)
    X = vectorizer.transform([message]).toarray()

    # Padding des séquences (si nécessaire)
    max_sequence_length = 100  # Choisir la longueur maximale de séquence que vous avez utilisée lors de l'entraînement
    X_pad = pad_sequences(X, padding='post', maxlen=max_sequence_length)

    # Prédiction avec le modèle LSTM
    y_pred = model.predict(X_pad)

    # Seuil pour classification binaire (Hate vs No Hate)
    is_hate = 1 if y_pred > 0.5 else 0  # Si la prédiction > 0.5, le message est considéré comme haineux

    return jsonify({'is_hate': is_hate})  # Retourne la prédiction sous forme de JSON

# Lancer le serveur
if __name__ == "__main__":
    app.run(debug=False, host='0.0.0.0', port=5000)
