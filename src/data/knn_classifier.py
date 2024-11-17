import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neighbors import NearestNeighbors
from db_connection import fetch_posts_data, fetch_user_likes
from sklearn.metrics.pairwise import cosine_similarity

# Función para preprocesar el texto y aplicar limpieza basica
def preprocess_text(text):
    # Convertir a minusculas y eliminar los espacios al principio y al final
    text = text.lower().strip()
    return text

# Funcion para obtener y preprocesar datos de posteos y "Likes" del usuario
def get_data_for_knn(user_id):
    # Obtener todos los posteos de la base de datos
    post_data = fetch_posts_data()
    # Obtener los posteos marcados como "Like" por el usuario especifico
    user_likes = fetch_user_likes(user_id)

    # Preprocesar el texto de cada posteo
    all_posts = [{'title': preprocess_text(post['title']), 'content': preprocess_text(post['content'])} for post in post_data]
    liked_posts = [{'title': preprocess_text(like['title']), 'content': preprocess_text(like['content'])} for like in user_likes]

    return all_posts, liked_posts

# Funcion para vectorizar y aplicar el modelo KNN
def knn_recommendations(user_id, n_recommendations=5):
    # Obtener y preprocesar datos
    all_post, liked_posts = get_data_for_knn(user_id)

    # Crear un dataframe para todos los posteos
    posts_df = pd.DataFrame(all_post)
    # Concatenar titulo y contenido
    posts_df['text'] = posts_df['title'] + " " + posts_df['content']

    # Crear un dataframe para los "Likes" del usuario
    likes_df = pd.DataFrame(liked_posts)
    likes_df['text'] = likes_df['title'] + " " + likes_df['content']

    # Vectorizar el texto usando TF-IDF
    vectorizer = TfidfVectorizer()
    tfidf_matrix = vectorizer.fit_transform(posts_df['text']) # Vectorizacion de todos los posteos
    liked_matrix = vectorizer.transform(likes_df['text']) # Vectorizacion de los posteos Liked por el usuario

    # Inicializar el modelo KNN
    knn = NearestNeighbors(n_neighbors=n_recommendations, metric='cousine')
    knn.fit(tfidf_matrix)

    # Encontrar los posteos mas similares a los "Likes" del usuario
    recommendations = []
    for i, liked_post_vector in enumerate(liked_matrix):
        distances, indices = knn.kneighbors(liked_post_vector, n_neighbors=n_recommendations)
        for index in indices.flatten():
            recommended_post = {
                'title'     :   posts_df.iloc[index]['title'],
                'content'   :   posts_df.iloc[index]['content'],
                'similarity_score'  :   cosine_similarity(liked_post_vector, tfidf_matrix[index])[0][0]
            }
            recommendations.append(recommended_post)
    
    # Ordenar las recomendaciones por puntaje de similitud
    recommendations = sorted(recommendations, key=lambda x: x['similarity_score'], reverse=True)
    return recommendations[:n_recommendations] # Limitar a las mejores recomendaciones