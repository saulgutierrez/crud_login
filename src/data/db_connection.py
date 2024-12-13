import mysql.connector
from mysql.connector import Error

def create_connection():
    try:
        connection = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='crud_login'
        )
        if connection.is_connected():
            print("Conexion exitosa a la base de datos")
            return connection
    except Error as e:
        print(f"Error al conectar a la base de datos: {e}")
        return None
    
def close_connection(connection):
    if connection.is_connected():
        connection.close()
        print("Conexion cerrada correctamente")

# Funcion para obtener los datos de los posteos (titulos y contenidos)
def fetch_posts_data():
    connection = create_connection()
    if connection is None:
        return []

    query = """
    SELECT 
        p.id_post, 
        p.id_autor, 
        p.autor_post, 
        p.titulo_post, 
        p.contenido_post,
        u.fotografia
    FROM 
        post p
    INNER JOIN 
        usuarios u ON p.id_autor = u.id
    """
    cursor = connection.cursor()
    posts_data = []

    try:
        cursor.execute(query)
        result = cursor.fetchall()
        for row in result:
            post = {
                'id_post': row[0],
                'id_autor': row[1],
                'autor_post': row[2],
                'titulo_post': row[3],
                'contenido_post': row[4],
                'fotografia_autor': row[5]
            }
            posts_data.append(post)
    except Error as e:
        print(f"Error al obtener los datos de los posteos: {e}")
    finally:
        cursor.close()
        close_connection(connection)

    return posts_data

# Funcion para obtener los datos de los posteos en los que un usuario especifico ha dado like
def fetch_user_likes(user_id):
    connection = create_connection()
    if connection is None:
        return []
    
    query = """
    SELECT 
        p.id_post, 
        p.id_autor, 
        p.autor_post, 
        p.titulo_post, 
        p.contenido_post,
        u.fotografia
    FROM 
        likes l
    INNER JOIN 
        post p ON l.liked_id_post = p.id_post
    INNER JOIN 
        usuarios u ON p.id_autor = u.id
    WHERE 
        l.liked_by = %s
    """
    cursor = connection.cursor()
    liked_posts = []

    try:
        cursor.execute(query, (user_id,))
        result = cursor.fetchall()
        for row in result:
            liked_post = {
                'id' :  row[0],
                'id_author' : row[1],
                'author' : row[2],
                'title' :   row[3],
                'content' : row[4],
                'author_photo' : row[5]
            }
            liked_posts.append(liked_post)
    except Error as e:
        print(f"Error al obtener los likes del usuario: {e}")
    finally:
        cursor.close()
        close_connection(connection)
    return liked_posts