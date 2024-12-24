import json
from dataset import generate_dataset
from knn_classifier_users import knn_recommendations

def save_results(results):
    with open("results_users.json", "w") as f:
        json.dump(results, f)

if __name__ == "__main__":
    dataset = generate_dataset()
    user_id = 1
    recommendations = knn_recommendations(user_id, dataset)
    save_results(recommendations)