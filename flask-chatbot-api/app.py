from flask import Flask, request, jsonify
from flask_cors import CORS
import mysql.connector
import nltk
import string
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import WordNetLemmatizer
from unidecode import unidecode
import json
import re
import logging
from random import choice
import random  # Added missing import
from functools import lru_cache
from datetime import datetime

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler("chatbot.log"),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Initialize NLTK resources
lemmatizer = WordNetLemmatizer()
stop_words = set(stopwords.words('french'))

# Initialisation de NLTK
try:
    nltk.download('punkt')
    nltk.download('stopwords')
    nltk.download('wordnet')
    nltk.download('omw-1.4')
except Exception as e:
    logger.error(f"Erreur lors du téléchargement des ressources NLTK: {e}")

# Initialisation de l'application Flask
app = Flask(__name__)
CORS(app, resources={r"/ask": {"origins": "http://127.0.0.1:8000"}})

# Initialize lemmatizer (was missing)
lemmatizer = WordNetLemmatizer()

# Fonction pour établir une connexion à la base de données
def get_db_connection():
    try:
        return mysql.connector.connect(
            host="localhost",
            user="root",
            password="",  # Remplacez par votre mot de passe MySQL
            database="shopall"
        )
    except Exception as e:
        logger.error(f"Erreur de connexion à la base de données: {e}")
        return None

# Charger les intentions depuis intents.json
try:
    with open('intents.json', 'r', encoding='utf-8') as file:
        intents_data = json.load(file)
except Exception as e:
    logger.error(f"Erreur lors du chargement du fichier intents.json: {e}")
    intents_data = {"intents": []}

# Prétraitement du texte
stop_words = set(stopwords.words('french'))

def extract_keywords(text):
    text = unidecode(text.lower())  # Normaliser les accents et mettre en minuscule
    tokens = word_tokenize(text)
    
    filtered = []
    for token in tokens:
        if token not in string.punctuation and token.isalpha():
            if token not in stop_words and len(token) > 2:
                lemma = lemmatizer.lemmatize(token)
                filtered.append(lemma.lower())
    
    return filtered

def generate_FAQ_response(message):
    for intent in intents_data["intents"]:
        for pattern in intent["patterns"]:
            # Vérifie si au moins un mot du pattern est dans le message
            if any(token in message for token in pattern.split()):  # Fixed: split pattern into tokens
                return random.choice(intent["responses"])
    
    return None  # Aucun intent reconnu
    

# Fonction pour détecter si c'est une demande de produit ou de catégorie
def is_product_request(message):
    db = get_db_connection()  # Added missing database connection
    if db is None:
        return []
        
    cursor = db.cursor()  # Added missing cursor initialization
    
    # Requête SQL pour récupérer noms et catégories depuis la BDD
    query = """
    SELECT nom as nom FROM produits
    UNION
    SELECT DISTINCT categorie as nom FROM produits
    ORDER BY nom;
    """
    cursor.execute(query)
    results = cursor.fetchall()
    product_keywords = [row[0].lower() for row in results]  # On s'assure que tout est en minuscule
    
    # Convert message to string if it's a list
    if isinstance(message, list):
        message_text = " ".join(message)
    else:
        message_text = message
        
    # Étape 1 : Vérifie si un mot-clé est dans le message directement
    for keyword in product_keywords:
        if keyword in message_text:
            return search_products(keyword)  # Changed to pass the keyword instead of product_keywords

    # Étape 2 : Vérifie si le message suit un pattern connu
    patterns = [
        r"je cherche (?:des|du|de la|un|une|les) ([a-zéèêëàâäôöùûüç\s]+)",
        r"montre[z]?-moi (?:des|du|de la|un|une|les) ([a-zéèêëàâäôöùûüç\s]+)",
        r"je veux (?:des|du|de la|un|une|les) ([a-zéèêëàâäôöùûüç\s]+)",
        r"avez[- ]vous (?:des|du|de la|un|une|les) ([a-zéèêëàâäôöùûüç\s]+)",
        r"où (?:puis-je trouver|sont|est) (?:des|du|de la|un|une|les) ([a-zéèêëàâäôöùûüç\s]+)",
        r"combien coûte[nt]? (?:des|du|de la|un|une|les) ([a-zéèêëàâäôöùûüç\s]+)",
        r"(?:recommand|suggèr|propos|indiqu|présent)(?:e|es|ez)?[- ]?moi (?:des|du|de la|un|une|les) ([a-zéèêëàâäôöùûüç\s]+)",
        r"donne(?:s)?[- ]?moi (?:des|du|de la|un|une|les) ([a-zéèêëàâäôöùûüç\s]+)"
    ]

    for pattern in patterns:
        match = re.search(pattern, message_text)
        if match:
            # On récupère le groupe capturé, on nettoie et enlève les accents
            potential_category = unidecode(match.group(1).strip())
            for keyword in product_keywords:
                if keyword in potential_category:
                    return search_products(keyword)  # Changed to pass the keyword instead of product_keywords

    # Étape 3 : Vérifie les catégories manuellement définies
    categories = {
        "telephone": ["telephone", "smartphone", "portable", "mobile", "iphone", "samsung", "huawei"],
        "electronique": ["electronique", "appareil", "appareils"],
        "vetements": ["vetement", "habit", "fringue"],
        "chaussures": ["chaussure", "basket", "bottes", "sandales"],
        "accessoires": ["accessoire", "sac", "ceinture", "bijou"],
        "maison": ["maison", "decoration", "mobilier", "meuble"],
        "cuisine": ["cuisine", "ustensile", "vaisselle", "electromenager"],
        "jardin": ["jardin", "jardinage", "outil"],
        "beaute": ["beaute", "cosmetique", "maquillage", "parfum", "soin"],
        "sante": ["sante", "bien-etre", "hygiene"],
        "sport": ["sport", "football", "basketball", "tennis", "jogging"],
        "jouets": ["jouet", "jeu", "poupee", "figurine", "peluche"],
        "livres": ["livre", "roman", "bd", "manga", "magazine"],
        "musique": ["musique", "cd", "vinyle", "instrument", "guitare", "piano"],
        "films": ["film", "dvd", "blu-ray", "serie"],
        "informatique": ["informatique", "ordinateur", "pc", "clavier", "souris", "ecran"],
    }

    for category, keywords in categories.items():
        for keyword in keywords:
            if keyword in message_text:
                return search_products(keyword)  # Changed to pass the keyword instead of keywords list

    cursor.close()
    db.close()
    return []

# Fonction de recherche des produits par mots-clés
def search_products(keywords):
    db = get_db_connection()
    if db is None:
        return []

    cursor = db.cursor(dictionary=True)
    results = []

    try:
        # S'assurer que keywords est une liste
        if isinstance(keywords, str):
            keywords = [keywords]

        for word in keywords:
            cursor.execute("""
                SELECT id, nom, prix_unitaire AS prix, categorie, quantite, vendeur_id, image
                FROM produits
                WHERE (nom LIKE %s OR categorie LIKE %s)
                AND quantite > 0
            """, (f"%{word}%", f"%{word}%"))
            results.extend(cursor.fetchall())

        # Éliminer les doublons par ID
        unique_results = []
        seen_ids = set()
        for prod in results:
            if prod['id'] not in seen_ids:
                unique_results.append(prod)
                seen_ids.add(prod['id'])

        return unique_results

    except Exception as e:
        logger.error(f"Erreur lors de la recherche avec les mots-clés {keywords}: {e}")
        return []

    finally:
        cursor.close()
        db.close()

# Added missing function for logging interactions
def log_interaction(client_id, question, interaction_type, result_count):
    try:
        db = get_db_connection()
        if db:
            cursor = db.cursor()
            cursor.execute("""
                INSERT INTO interaction_logs (client_id, question, interaction_type, result_count, timestamp)
                VALUES (%s, %s, %s, %s, %s)
            """, (client_id, question, interaction_type, result_count, datetime.now()))
            db.commit()
            cursor.close()
            db.close()
    except Exception as e:
        logger.error(f"Erreur lors de l'enregistrement de l'interaction: {e}")

# Fonction pour vérifier si le message est une demande de recommandation
def is_recommendation(messages):
    keywords = ["recommande", "suggère", "propos", "indique", "présente"]

    for message in messages:
        for keyword in keywords:
            pattern = rf"\b{keyword}\b"
            if re.search(pattern, message):
                return True
    return False
    

# Fonction pour obtenir des recommandations personnalisées
@lru_cache(maxsize=32)
def get_personalized_recommendations(client_id, is_recommendation):
    if not is_recommendation:
        return []

    if not client_id or client_id == "null":
        return []

    db = get_db_connection()
    if not db:
        return []

    cursor = db.cursor(dictionary=True)
    
    try:
        # Obtenir les produits les plus cliqués par le client
        cursor.execute("""
            SELECT p.id, p.nom, p.prix_unitaire as prix, p.categorie, p.quantite, p.vendeur_id, p.image
            FROM produits p
            JOIN (
                SELECT categorie
                FROM compteurs
                WHERE client_id = %s
                ORDER BY nombre_clique DESC
                LIMIT 3
            ) c ON p.categorie = c.categorie
            WHERE p.quantite > 0
            ORDER BY RAND()
            LIMIT 5
        """, (client_id,))
        
        # Convertir les résultats en format dictionnaire
        produits = cursor.fetchall()
        return [{
            'id': produit['id'],
            'nom': produit['nom'],
            'prix': produit['prix'],
            'categorie': produit['categorie'],
            'quantite': produit['quantite'],
            'vendeur_id': produit['vendeur_id'],
            'image': produit['image']
        } for produit in produits] or []
    except Exception as e:
        logger.error(f"Erreur lors de la récupération des recommandations personnalisées: {e}")
        return []
    finally:
        cursor.close()
        db.close()

# Fonction pour obtenir des recommandations populaires (pour utilisateurs non connectés)
@lru_cache(maxsize=1)  # Cache le résultat pendant 1 heure (mise à jour lors du redémarrage du serveur)
def get_popular_recommendations():
    db = get_db_connection()
    if not db:
        return []
        
    cursor = db.cursor(dictionary=True)
    
    try:
        # Obtenir les produits les plus populaires basés sur le nombre total de clics par catégorie
        cursor.execute("""
            SELECT p.id, p.nom, p.prix_unitaire as prix, p.categorie, p.quantite, p.vendeur_id, p.image
            FROM produits p
            JOIN (
                SELECT categorie, SUM(nombre_clique) as total_clics
                FROM compteurs
                GROUP BY categorie
                ORDER BY total_clics DESC
                LIMIT 1
            ) as pop_cat ON p.categorie = pop_cat.categorie
            WHERE p.quantite > 0
            ORDER BY p.prix_unitaire ASC
            LIMIT 5
        """)
        
        return cursor.fetchall()
    except Exception as e:
        logger.error(f"Erreur lors de la récupération des recommandations populaires: {e}")
        return []
    finally:
        cursor.close()
        db.close()

# Route API pour le chatbot
@app.route('/ask', methods=['POST'])
def chatbot():
    # Récupérer le message envoyé par l'utilisateur et l'ID client
    data = request.get_json()
    question = data.get('message', '')
    client_id = data.get('client_id')
    
    logger.info(f"Nouvelle requête: client_id={client_id}, message='{question}'")

    keywords = extract_keywords(question)

    produits = is_product_request(keywords)
    if not produits:
        produits = get_personalized_recommendations(client_id, is_recommendation(keywords))
        if not produits:
            message = generate_FAQ_response(keywords)
            if not message:
                message = "Je ne suis pas sûr de comprendre. Pouvez-vous reformuler votre question ?"
            log_interaction(client_id, question, "category_search", len(message))
            return jsonify({
                "status": "intent",
                "response": message
            })
        else:
            log_interaction(client_id, question, "category_search", len(produits))
            return jsonify({
                "status": "ok",
                "produits": produits,
                "message": "Voici des produits recommandés pour vous :"
            })    
    else:
        # Get the category from the first product if available
        category = produits[0]['categorie'] if produits and 'categorie' in produits[0] else "recherchée"
        log_interaction(client_id, question, "category_search", len(produits))
        return jsonify({
            "status": "ok",
            "produits": produits,
            "message": f"Voici des produits de la catégorie {category} :"
        })
    
# Lancer l'application Flask
if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)