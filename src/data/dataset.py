import pandas as pd
from db_setup import connect_to_db

def generate_dataset():
    connection = connect_to_db()

    # Query: Seguidores en común
    query_followers = """
    SELECT 
        s1.id_seguidor AS user1,
        s2.id_seguidor AS user2,
        COUNT(*) AS seguidores_comunes
    FROM siguiendo AS s1
    JOIN siguiendo AS s2 ON s1.id_seguido = S2.id_seguido
    WHERE s1.id_seguidor != s2.id_seguidor
    GROUP BY s1.id_seguidor, s2.id_seguidor;
    """
    followers = pd.read_sql(query_followers, connection)

    # Query: Likes en publicaciones
    query_likes = """
    SELECT
        p.id_autor AS post_owner,
        l.liked_by AS liker,
        COUNT(*) AS likes
    FROM post AS p
    JOIN likes AS l ON p.id_post = l.liked_id_post
    WHERE p.id_autor != l.liked_by
    GROUP BY p.id_autor, l.liked_by;
    """
    likes = pd.read_sql(query_likes, connection)

    # Query: Informacion de usuarios
    query_users = """
    SELECT id, usuario
    FROM usuarios;
    """

    users = pd.read_sql(query_users, connection)

    # Merge datasets
    dataset = pd.merge(followers, likes, left_on=['user1', 'user2'], right_on=['post_owner', 'liker'], how='outer').fillna(0)
    dataset['seguidores_comunes'] = dataset['seguidores_comunes'].astype(int)
    dataset['likes'] = dataset['likes'].astype(int)

    # Merge con informacion de usuarios
    dataset = pd.merge(dataset, users, left_on='user2', right_on='id', how='left')
    
    connection.close()
    return dataset