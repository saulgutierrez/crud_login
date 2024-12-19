from sklearn.neighbors import NearestNeighbors
import pandas as pd

def knn_recommendations(user_id, dataset, k=5):
    users = dataset[['seguidores_comunes', 'likes']]
    model = NearestNeighbors(n_neighbors=k)
    model.fit(users)

    distances, indices = model.kneighbors([dataset.loc[user_id, ['seguidores_comunes', 'likes']]])
    recommendations = dataset.iloc[indices[0]]
    return recommendations.to_dict('records')