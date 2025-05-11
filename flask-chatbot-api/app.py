from flask import Flask, request, jsonify
from flask_cors import CORS
import mysql.connector
import nltk
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords

# Initialisation de NLTK
nltk.download('punkt')
nltk.download('stopwords')

# Initialisation de l'application Flask
app = Flask(__name__)
CORS(app, resources={r"/ask": {"origins": "http://127.0.0.1:8000"}})

# Connexion à la base de données MySQL
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",  # Remplacez par votre mot de passe MySQL
    database="shopall"
)
cursor = db.cursor(dictionary=True)

# Prétraitement du texte
stop_words = set(stopwords.words('french'))

def extract_keywords(text):
    tokens = word_tokenize(text.lower())
    return [t for t in tokens if t not in stop_words]

# Fonction de recherche des produits
def search_products(keywords):
    results = []
    for word in keywords:
        cursor.execute("SELECT id, nom, prix_unitaire as prix, categorie, quantite, vendeur_id FROM produits WHERE categorie LIKE %s AND quantite > 0", (f"%{word}%",))
        results.extend(cursor.fetchall())
    return results

# Route API pour le chatbot
@app.route('/ask', methods=['POST'])
def chatbot():
    # Récupérer le message envoyé par l'utilisateur
    data = request.get_json()
    question = data.get('message', '')
    
    # Extraire les mots-clés de la question
    keywords = extract_keywords(question)
    
    # Recherche des produits correspondants
    produits = search_products(keywords)
    
    # Retourner la réponse sous forme JSON
    if produits:
        return jsonify({"status": "ok", "produits": produits})
    else:
        return jsonify({"status": "vide", "message": "Aucun produit trouvé pour cette catégorie."})

# Lancer l'application Flask
if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
