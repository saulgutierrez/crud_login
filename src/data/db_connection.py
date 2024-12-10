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

    query = "SELECT id_post, autor_post, titulo_post, contenido_post FROM post"
    cursor = connection.cursor()
    posts_data = []

    try:
        cursor.execute(query)
        result = cursor.fetchall()
        for row in result:
            post = {
                'id_post':      row[0],
                'autor_post':   row[1],
                'titulo_post':    row[2],
                'contenido_post':   row[3]
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
    
    query = """SELECT post.id_post, post.autor_post, post.titulo_post, post.contenido_post FROM likes INNER JOIN post ON likes.liked_id_post = post.id_post WHERE likes.liked_by = %s"""
    cursor = connection.cursor()
    liked_posts = []

    try:
        cursor.execute(query, (user_id,))
        result = cursor.fetchall()
        for row in result:
            liked_post = {
                'id' :  row[0],
                'author' : row[1],
                'title' :   row[2],
                'content' : row[3]
            }
            liked_posts.append(liked_post)
    except Error as e:
        print(f"Error al obtener los likes del usuario: {e}")
    finally:
        cursor.close()
        close_connection(connection)
    return liked_posts