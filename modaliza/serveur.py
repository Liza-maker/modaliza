import pymysql
pymysql.install_as_MySQLdb()

from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from tensorflow.keras.models import load_model
from sklearn.feature_extraction.text import TfidfVectorizer
from flask_cors import CORS
import pickle

# Charger le vectorizer sauvegardé
with open('vectorizer.pkl', 'rb') as f:
    vectorizer = pickle.load(f)

# Charger le modèle pré-entraîné
model = load_model('hate_speech_model.keras')

# Initialiser Flask
app = Flask(__name__)

# Activer CORS
CORS(app)

# Configuration de la base de données SQLAlchemy
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:root@localhost/modaliza'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db = SQLAlchemy(app)

# Définition du modèle SQLAlchemy pour la table 'messages'
class Message(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    message = db.Column(db.Text, nullable=False)
    date_enregistrement = db.Column(db.DateTime, default=db.func.current_timestamp())

# Route pour la prédiction du message et l'enregistrement dans la base de données
@app.route('/send-message', methods=['POST'])
def send_message():
    data = request.get_json()  # Récupérer les données envoyées par l'API via POST
    message = data['message']  # Récupérer le message

    # Prétraiter le message (vectorisation)
    X = vectorizer.transform([message]).toarray()

    # Prédiction avec le modèle
    y_pred = model.predict(X)
    is_hate = 1 if y_pred > 0.7 else 0  # Seuil pour classification binaire

    # Enregistrer le message dans la base de données si ce n'est pas haineux
    if is_hate == 0:
        new_message = Message(message=message)
        db.session.add(new_message)
        db.session.commit()

    # Retourner la prédiction sous forme de JSON
    return jsonify({'is_hate': is_hate})

# Route pour récupérer les messages non haineux
@app.route('/get-messages', methods=['GET'])
def get_messages():
    # Récupérer tous les messages de la table 'MESSAGE'
    messages = Message.query.order_by(Message.date_enregistrement.asc()).all()
    # Retourner les messages sous forme de JSON
    return jsonify({
        'messages': [
            {
                'id': msg.id,
                'message': msg.message,
                'date_enregistrement': msg.date_enregistrement.strftime('%Y-%m-%d %H:%M:%S')
            }
            for msg in messages
        ]
    })


if __name__ == "__main__":
    app.run(debug=False, host='0.0.0.0', port=5000)
