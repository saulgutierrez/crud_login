import pandas as pd
import numpy as np
import json
import argparse
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neighbors import NearestNeighbors
# Llamada al archivo de conexion para obtener la informacion
from db_connection import fetch_posts_data, fetch_user_likes
from sklearn.metrics.pairwise import cosine_similarity
# Llamada al archivo preprocess.py para limpieza de datos
from preprocess import preprocess_text

# Funcion para obtener y preprocesar datos de posteos y "Likes" del usuario
def get_data_for_knn(user_id):
    # Obtener todos los posteos de la base de datos
    post_data = fetch_posts_data()
    # Obtener los posteos marcados como "Like" por el usuario especifico
    user_likes = fetch_user_likes(user_id)

    # Preprocesar el texto de cada posteo
    all_posts = [{'id': post['id_post'], 'author': post['autor_post'], 'title': preprocess_text(post['titulo_post']), 'content': preprocess_text(post['contenido_post'])} for post in post_data]
    liked_posts = [{'id': like['id'], 'author': like['author'], 'title': preprocess_text(like['title']), 'content': preprocess_text(like['content'])} for like in user_likes]

    return all_posts, liked_posts

# Funcion para vectorizar y aplicar el modelo KNN
def knn_recommendations(user_id, n_recommendations=5):
    # Obtener y preprocesar datos
    all_post, liked_posts = get_data_for_knn(user_id)

    # Crear un dataframe para todos los posteos
    posts_df = pd.DataFrame(all_post)
    # Concatenar titulo y contenido
    posts_df['text'] = posts_df['author'] + " " + posts_df['title'] + " " + posts_df['content']

    # Crear un dataframe para los "Likes" del usuario
    likes_df = pd.DataFrame(liked_posts)
    likes_df['text'] = likes_df['author'] + " " + likes_df['title'] + " " + likes_df['content']

    # Vectorizar el texto usando TF-IDF
    vectorizer = TfidfVectorizer()
    tfidf_matrix = vectorizer.fit_transform(posts_df['text']) # Vectorizacion de todos los posteos
    liked_matrix = vectorizer.transform(likes_df['text']) # Vectorizacion de los posteos Liked por el usuario

    # Inicializar el modelo KNN
    knn = NearestNeighbors(n_neighbors=n_recommendations, metric='cosine')
    knn.fit(tfidf_matrix)

    # Encontrar los posteos mas similares a los "Likes" del usuario
    recommendations = []
    for i, liked_post_vector in enumerate(liked_matrix):
        distances, indices = knn.kneighbors(liked_post_vector, n_neighbors=n_recommendations)
        for index in indices.flatten():
            recommended_post = {
                'id'        :   posts_df.iloc[index]['id'],
                'author'    :   posts_df.iloc[index]['author'],
                'title'     :   posts_df.iloc[index]['title'],
                'content'   :   posts_df.iloc[index]['content'],
                'similarity_score'  :   cosine_similarity(liked_post_vector, tfidf_matrix[index])[0][0]
            }
            recommendations.append(recommended_post)
    
    # Ordenar las recomendaciones por puntaje de similitud
    recommendations = sorted(recommendations, key=lambda x: x['similarity_score'], reverse=True)
    return recommendations[:n_recommendations] # Limitar a las mejores recomendaciones

def save_results(results):
     # Convertir valores no serializables
    for rec in recommendations:
        for key, value in rec.items():
            if isinstance(value, (np.int64, np.float64)):  # Convertir tipos de NumPy
                rec[key] = value.item()
    
    # Escribir en un archivo JSON
    with open("results.json", "w") as f:
        json.dump(results, f)

if __name__ == "__main__":
    # Recuperamos el id del usuario logueado del argumento pasado como parametro
    # y mostramos las recomendaciones propias de cada usuario
    parser = argparse.ArgumentParser(description="KNN Recommendation Script")
    parser.add_argument("user_id", type=int, help="ID del usuario logueado")
    args = parser.parse_args()
    user_id = args.user_id
    recommendations = knn_recommendations(user_id)
    for idx, rec in enumerate(recommendations, 1):
        print(f"Recomendacion {idx}:")
        print(f"ID post: {rec['id']}")
        print(f"Autor: {rec['author']}")
        print(f"Titulo: {rec['title']}")
        print(f"Contenido: {rec['content']}")
        print(f"Puntaje de similitud: {rec['similarity_score']:.4f}")
        print()

        save_results(recommendations)