import os
import pandas as pd

# Chemins vers les données
text_folder = 'sampled_train/sampled_train'  # Sous-dossier contenant les fichiers texte
excel_file = 'sampled_train/data.csv'  # Chemin vers le fichier Excel

# Charger le fichier Excel (ou CSV)
data_labels = pd.read_csv(excel_file)

# Afficher un aperçu du fichier Excel pour vérifier la structure
print(data_labels.head())

# Initialiser les listes pour stocker les textes et labels
texts, labels = [], []

# Parcourir les lignes du fichier Excel
for index, row in data_labels.iterrows():
    file_id = row['file_id']  # Exemple : '12834217_1'
    label = row['label']     # Exemple : 'noHate' ou 'hate'

    # Extraire la partie du file_id pour trouver le nom du fichier texte correspondant
    # Exemple : '12834217_1' -> '12834217_1.txt' (c'est une hypothèse que vous avez ce format)
    file_name = f"{file_id}.txt"  # Ajouter l'extension .txt

    # Chemin complet vers le fichier texte
    file_path = os.path.join(text_folder, file_name)
    
    # Vérifier que le fichier texte existe avant de le lire
    if os.path.exists(file_path):  # Vérifie que le fichier existe
        with open(file_path, 'r', encoding='utf-8') as f:
            text_content = f.read().strip()
        
        # Ajouter le texte et le label correspondant
        texts.append(text_content)
        labels.append(1 if label == 'hate' else 0)  # 1 pour "hate", 0 pour "noHate"
        print(f"Chargement du fichier : {file_path}")
    else:
        print(f"Le fichier {file_path} n'existe pas. Il sera ignoré.")  # Affiche un message d'alerte pour les fichiers manquants

# Vérifier la taille des données chargées
print(f"Nombre de textes chargés : {len(texts)}")
print(f"Nombre de labels chargés : {len(labels)}")

# Afficher un échantillon des textes et des labels pour vérification
print(texts[:5])  # Affiche les 5 premiers textes
print(labels[:5])  # Affiche les 5 premiers labels

# Vérification du nombre de textes et labels
assert len(texts) == len(labels), "Le nombre de textes et de labels ne correspond pas."

import nltk
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import WordNetLemmatizer

# Télécharger les ressources nécessaires
nltk.download('punkt')
nltk.download('stopwords')
nltk.download('wordnet')
nltk.download('punkt_tab')

# Initialiser le lemmatiseur et la liste de stop words
lemmatizer = WordNetLemmatizer()
stop_words = set(stopwords.words('english'))

# Fonction de prétraitement
def preprocess_text(text):
    # Tokeniser le texte
    tokens = word_tokenize(text.lower())  # Convertir en minuscules
    # Supprimer les stop words et lemmatiser
    processed_tokens = [lemmatizer.lemmatize(word) for word in tokens if word not in stop_words and word.isalpha()]
    return " ".join(processed_tokens)

# Appliquer le prétraitement sur tous les textes
texts = [preprocess_text(text) for text in texts]

# Afficher un échantillon de texte prétraité
print(texts[:5])

from nltk.corpus import stopwords
from sklearn.feature_extraction.text import TfidfVectorizer

# Charger les stopwords
stop_words = set(stopwords.words('english'))

# Filtrer les mots
filtered_texts = []
for text in texts:
    words = nltk.word_tokenize(text)
    filtered_words = [word for word in words if word.lower() not in stop_words]
    filtered_texts.append(' '.join(filtered_words))

# Afficher un exemple de texte filtré
print(filtered_texts[:5])

# Vectorisation des textes avec TfidfVectorizer
vectorizer = TfidfVectorizer(max_features=5000)  # Limiter le nombre de features à 5000
X = vectorizer.fit_transform(filtered_texts).toarray()

# Afficher la forme de la matrice de caractéristiques
print("Forme de la matrice X (features):", X.shape)

# Maintenant, vous pouvez continuer avec l'entraînement de votre modèle LSTM.
