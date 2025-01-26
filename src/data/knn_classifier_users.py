from sklearn.neighbors import NearestNeighbors
import pandas as pd

def knn_recommendations(user_id, dataset, k=3):
    users = dataset[['seguidores_comunes', 'likes']]
    
    # Entrenar el modelo con un DataFrame
    model = NearestNeighbors(n_neighbors=k)
    model.fit(users)

    # Crear un DataFrame con los datos del usuario
    user_data = pd.DataFrame([{
        'seguidores_comunes': dataset.loc[dataset['user1'] == user_id, 'seguidores_comunes'].sum(),
        'likes': dataset.loc[dataset['user1'] == user_id, 'likes'].sum()
    }])
    
    # Predecir vecinos más cercanos
    distances, indices = model.kneighbors(user_data)
    # Retorna la fila del Dataframe que nos sirven como resultado en el proceso knn
    recommendations = dataset.iloc[indices[0]]
    return recommendations[['user2', 'usuario', 'fotografia', 'seguidores_comunes', 'likes']].to_dict('records')