from flask import Flask, request, jsonify
from flask_cors import CORS
import mysql.connector
import nltk
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
import json
import re
import logging
from functools import lru_cache
from unidecode import unidecode  # Pour la normalisation des caractères accentués

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

# Initialisation de NLTK
try:
    nltk.download('punkt')
    nltk.download('stopwords')
except Exception as e:
    logger.error(f"Erreur lors du téléchargement des ressources NLTK: {e}")

# Initialisation de l'application Flask
app = Flask(__name__)
CORS(app, resources={r"/ask": {"origins": "http://127.0.0.1:8000"}})

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
    tokens = word_tokenize(text.lower())
    return [t for t in tokens if t not in stop_words and len(t) > 2]

def detect_intent(message):
    message_lower = message.lower()
    for intent in intents_data['intents']:
        for pattern in intent['patterns']:
            if pattern.lower() in message_lower:
                return intent
    return None

# Fonction pour extraire la catégorie de la demande
def extract_category(message):
    message_lower = unidecode(message.lower())  # Normalise les caractères accentués
    
    # Dictionnaire des catégories et leurs variantes/alias
    categories = {
        "telephone": ["telephone", "smartphone", "portable", "mobile", "iphone", "samsung", "huawei"],
        "electronique": ["electronique", "electroniques", "appareil electronique", "appareils electroniques"],
        "vetements": ["vetement", "vetements", "habit", "habits", "fringue", "fringues"],
        "chaussures": ["chaussure", "chaussures", "basket", "baskets", "bottes", "sandales"],
        "accessoires": ["accessoire", "accessoires", "sac", "sacs", "ceinture", "ceintures", "bijou", "bijoux"],
        "maison": ["maison", "decoration", "décoration", "mobilier", "meuble", "meubles"],
        "cuisine": ["cuisine", "ustensile", "ustensiles", "vaisselle", "electromenager", "électroménager"],
        "jardin": ["jardin", "jardinage", "outil de jardin", "outils de jardin"],
        "beaute": ["beaute", "beauté", "cosmetique", "cosmétique", "maquillage", "parfum", "soin", "soins"],
        "sante": ["sante", "santé", "bien-etre", "bien être", "hygiene", "hygiène"],
        "sport": ["sport", "sportif", "sportive", "football", "basketball", "tennis", "course", "jogging"],
        "jouets": ["jouet", "jouets", "jeu", "jeux", "poupee", "poupée", "figurine", "peluche"],
        "livres": ["livre", "livres", "roman", "bd", "manga", "magazine", "revue"],
        "musique": ["musique", "cd", "vinyle", "vinyles", "instrument", "instruments", "guitare", "piano"],
        "films": ["film", "films", "dvd", "bluray", "blu-ray", "serie", "séries", "blu ray"],
        "informatique": ["informatique", "ordinateur", "pc", "portable", "clavier", "souris", "ecran", "écran"]
    }
    
    # Vérifier si une catégorie est mentionnée explicitement
    for category, keywords in categories.items():
        for keyword in keywords:
            if keyword in message_lower:
                return category
    
    # Utiliser des patterns pour extraire des catégories
    patterns = [
        r"(?:je cherche|montre[z]?-moi|je veux|avez[- ]vous|où puis-je trouver|combien coûte[nt]?)\s*(?:des?|les?|du|de la|aux?|au(x)?)?\s*([a-zéèêëàâäôöùûüç\s]+)",
        r"(?:recherche|trouver|voir|afficher|recommander|conseiller|suggérer)\s*(?:des?|les?|du|de la|aux?|au(x)?)?\s*([a-zéèêëàâäôöùûüç\s]+)"
    ]
    
    for pattern in patterns:
        match = re.search(pattern, message_lower)
        if match:
            # Prend le premier groupe capturé (sans les mots de liaison)
            potential_category = unidecode(match.group(1).strip())
            # Vérifie si ce mot correspond à une catégorie connue
            for category, keywords in categories.items():
                for keyword in keywords:
                    if keyword in potential_category:
                        return category
    
    return None

# Fonction de recherche des produits par mots-clés
def search_products(keywords):
    db = get_db_connection()
    if not db:
        return []
        
    cursor = db.cursor(dictionary=True)
    results = []
    
    try:
        for word in keywords:
            if len(word) < 3:  # Ignorer les mots trop courts
                continue
            cursor.execute("SELECT id, nom, prix_unitaire as prix, categorie, quantite, vendeur_id, image FROM produits WHERE (nom LIKE %s OR categorie LIKE %s) AND quantite > 0", (f"%{word}%", f"%{word}%"))
            results.extend(cursor.fetchall())
        
        # Éliminer les doublons (par ID)
        unique_results = []
        seen_ids = set()
        for prod in results:
            if prod['id'] not in seen_ids:
                unique_results.append(prod)
                seen_ids.add(prod['id'])
        
        return unique_results
    except Exception as e:
        logger.error(f"Erreur lors de la recherche de produits: {e}")
        return []
    finally:
        cursor.close()
        db.close()

# Fonction pour obtenir des recommandations personnalisées
@lru_cache(maxsize=32)  # Cache les résultats pour améliorer les performances
def get_personalized_recommendations(client_id):
    if not client_id or client_id == "null":
        return []
        
    db = get_db_connection()
    if not db:
        return []
        
    cursor = db.cursor(dictionary=True)
    
    try:
        # Utiliser la requête SQL fournie pour obtenir les produits de la catégorie la plus cliquée
        cursor.execute("""
            SELECT p.id, p.nom, p.prix_unitaire as prix, p.categorie, p.quantite, p.vendeur_id, p.image, c.nombre_clique
            FROM produits p
            JOIN compteurs c ON p.categorie = c.categorie
            WHERE c.client_id = %s
            AND c.nombre_clique = (
                SELECT MAX(nombre_clique)
                FROM compteurs
                WHERE client_id = %s
            )
            AND p.quantite > 0
            ORDER BY p.prix_unitaire ASC
            LIMIT 5
        """, (client_id, client_id))
        
        return cursor.fetchall()
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

# Fonction pour mettre à jour le compteur de clics d'une catégorie
def update_category_click(client_id, category):
    if not client_id or client_id == "null" or not category:
        return False
        
    db = get_db_connection()
    if not db:
        return False
        
    cursor = db.cursor()
    
    try:
        # Vérifier si un compteur existe déjà pour ce client et cette catégorie
        cursor.execute(
            "SELECT id FROM compteurs WHERE client_id = %s AND categorie = %s",
            (client_id, category)
        )
        result = cursor.fetchone()
        
        if result:
            # Mettre à jour le compteur existant
            cursor.execute(
                "UPDATE compteurs SET nombre_clique = nombre_clique + 1, date_derniere_modification = %s WHERE client_id = %s AND categorie = %s",
                (datetime.now(), client_id, category)
            )
        else:
            # Créer un nouveau compteur
            cursor.execute(
                "INSERT INTO compteurs (client_id, categorie, nombre_clique, date_creation, date_derniere_modification) VALUES (%s, %s, 1, %s, %s)",
                (client_id, category, datetime.now(), datetime.now())
            )
        
        db.commit()
        return True
    except Exception as e:
        logger.error(f"Erreur lors de la mise à jour du compteur de clics: {e}")
        db.rollback()
        return False
    finally:
        cursor.close()
        db.close()

# Fonction pour enregistrer l'interaction avec le chatbot
def log_interaction(client_id, message, response_type, products_count=0):
    db = get_db_connection()
    if not db:
        return
        
    cursor = db.cursor()
    
    try:
        cursor.execute(
            "INSERT INTO chatbot_logs (client_id, message, response_type, products_count, timestamp) VALUES (%s, %s, %s, %s, %s)",
            (client_id or "anonymous", message, response_type, products_count, datetime.now())
        )
        db.commit()
    except Exception as e:
        logger.error(f"Erreur lors de l'enregistrement de l'interaction: {e}")
        db.rollback()
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
    
    # 1. Vérifier d'abord les intentions FAQ (sauf si c'est une demande de recommandation)
    if not any(word in question.lower() for word in ["recommande", "suggère", "propose", "conseille", "recommandation"]):
        intent = detect_intent(question)
        if intent and intent['tag'] not in ['produits', 'default']:
            log_interaction(client_id, question, "intent")
            return jsonify({
                "status": "intent", 
                "response": random.choice(intent['responses'])
            })
    
    # 2. Vérifier si c'est une demande de catégorie spécifique
    category = extract_category(question)
    if category:
        if client_id and client_id != "null":
            update_category_click(client_id, category)
        
        # Rechercher des produits dans cette catégorie
        produits = search_products([category])
        if produits:
            log_interaction(client_id, question, "category_search", len(produits))
            return jsonify({
                "status": "ok",
                "produits": produits,
                "message": f"Voici des produits de la catégorie {category} :"
            })
    
    # 3. Vérifier si c'est une demande de recommandation
    if any(word in question.lower() for word in ["recommande", "suggère", "propose", "conseille", "recommandation"]):
        # Si une catégorie est mentionnée, chercher des produits de cette catégorie
        if category:
            produits = search_products([category])
            if produits:
                log_interaction(client_id, question, "category_recommendations", len(produits))
                return jsonify({
                    "status": "ok",
                    "produits": produits,
                    "message": f"Voici des recommandations de {category} :"
                })
        
        # Sinon, faire des recommandations personnalisées ou populaires
        if client_id and client_id != "null":
            recommendations = get_personalized_recommendations(client_id)
            if recommendations:
                log_interaction(client_id, question, "personalized_recommendations", len(recommendations))
                return jsonify({
                    "status": "ok",
                    "produits": recommendations,
                    "message": "Voici des recommandations personnalisées pour vous :"
                })
        
        # Si pas de recommandations personnalisées ou utilisateur non connecté
        popular_products = get_popular_recommendations()
        if popular_products:
            log_interaction(client_id, question, "popular_recommendations", len(popular_products))
            return jsonify({
                "status": "ok",
                "produits": popular_products,
                "message": "Voici quelques produits populaires qui pourraient vous intéresser :"
            })
    
    # Si on arrive ici et qu'il y avait une catégorie mais pas de produits
    if category:
        return jsonify({
            "status": "vide",
            "message": f"Aucun produit trouvé dans la catégorie {category}."
        })
    
    # 4. Si c'est une recherche de produit générique
    keywords = extract_keywords(question)
    
    # Recherche des produits correspondants
    produits = search_products(keywords)
    
    if produits:
        log_interaction(client_id, question, "product_search", len(produits))
        return jsonify({
            "status": "ok",
            "produits": produits,
            "message": "Voici les produits qui correspondent à votre recherche :"
        })
    else:
        # Si aucun produit trouvé mais client connecté, proposer des recommandations
        if client_id and client_id != "null":
            recommendations = get_personalized_recommendations(client_id)
            if recommendations:
                log_interaction(client_id, question, "personalized_recommendations", len(recommendations))
                return jsonify({
                    "status": "ok",
                    "produits": recommendations,
                    "message": "Je n'ai pas trouvé de produits correspondant à votre recherche, mais voici quelques recommandations basées sur vos préférences."
                })
        
        # Si client non connecté ou pas de recommandations personnalisées, proposer des produits populaires
        popular_products = get_popular_recommendations()
        if popular_products:
            log_interaction(client_id, question, "popular_recommendations", len(popular_products))
            return jsonify({
                "status": "ok",
                "produits": popular_products,
                "message": "Voici quelques produits populaires qui pourraient vous intéresser :"
            })
        
        log_interaction(client_id, question, "no_products")
        return jsonify({
            "status": "vide",
            "message": "Désolé, je n'ai pas trouvé de produits correspondant à votre recherche."
        })

# Lancer l'application Flask
if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)