import json
import argparse
from dataset import generate_dataset
from knn_classifier_users import knn_recommendations

def save_results(results):
    with open("results_users.json", "w") as f:
        json.dump(results, f)

if __name__ == "__main__":
    # Parsear argumentos del script
    parser = argparse.ArgumentParser(description="KNN Recommendation Script")
    parser.add_argument("user_id", type=int, help="ID del usuario logueado")
    args = parser.parse_args()
    # Recuperar el id del usuario logueado
    user_id = args.user_id
    # Generar el dataset y obtener recomendaciones
    dataset = generate_dataset()
    recommendations = knn_recommendations(user_id, dataset)
    save_results(recommendations)