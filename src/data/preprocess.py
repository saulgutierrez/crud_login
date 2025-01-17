import re
import string
import nltk
from nltk.corpus import stopwords
from nltk.stem import WordNetLemmatizer
from bs4 import BeautifulSoup

# Descargar recursos necesarios de NLTK la primera vez que se usa el archivo
nltk.download('stopwords')
nltk.download('wordnet')

# Inicializar el lematizador y obtener las stop words
lemmatizer = WordNetLemmatizer()
stop_words = set(stopwords.words('spanish'))

# Elimina puntuacion del texto
def remove_punctuation(text):
    return text.translate(str.maketrans('', '', string.punctuation))

# Elimina las stopwords del texto
def remove_stopwords(text):
    words = text.split()
    filtered_words = [word for word in words if word not in stop_words]
    return ' '.join(filtered_words)

# Reduce las palabras a sus formas base (lemmatizacion)
def lemmatize_text(text):
    words = text.split()
    lemmatized_words = [lemmatizer.lemmatize(word) for word in words]
    return ' '.join(lemmatized_words)

# Remover etiquetas HTML
def remove_html_tags_and_special_chars(text):
    # Eliminar etiquetas HTML
    text = BeautifulSoup(text, "html.parser")
    text = text.get_text()
    text = re.sub(r'[^a-zA-Z0-9\s]', '', text)
    return text


# Aplica los pasos de preprocesamiento de texto en secuencia:
# - Convierte a minusculas
# - Elimina caracteres especiales y puntuacion
# - Elimina stop words
# - Aplica lematizacion
def preprocess_text(text):
    # Convertir a minusculas
    text = text.lower().strip()

    # Eliminar caracteres HTML
    text = remove_html_tags_and_special_chars(text)
    
    # Eliminar caracteres especiales
    text = re.sub(r'\s+', ' ', text)  # Eliminar espacios adicionales
    text = re.sub(r'[0-9]', '', text) # Eliminar dígitos

    # Eliminar puntuacion
    text = remove_punctuation(text)

    # Eliminar stop words
    text = remove_stopwords(text)

    # Aplicar lematizacion
    text = lemmatize_text(text)

    return text