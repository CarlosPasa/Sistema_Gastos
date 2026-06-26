import requests

from config import API_URL, EMAIL, PASSWORD


def login():
    response = requests.post(
        f"{API_URL}/login",
        json={
            "email": EMAIL,
            "password": PASSWORD
        },
        timeout=30
    )

    response.raise_for_status()

    data = response.json()

    return data["token"]
