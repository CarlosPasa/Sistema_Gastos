import requests

from config import API_URL


def get_headers(token):
    return {
        "Authorization": f"Bearer {token}",
        "Accept": "application/json",
    }


def extract_paginated(endpoint, token):
    url = f"{API_URL}/{endpoint}"
    records = []

    while url:
        response = requests.get(
            url,
            headers=get_headers(token),
            timeout=30
        )

        response.raise_for_status()

        json_response = response.json()

        if isinstance(json_response, list):
            records.extend(json_response)
            url = None

        elif isinstance(json_response, dict):
            records.extend(json_response.get("data", []))
            url = json_response.get("next_page_url")

        else:
            url = None

    return records


def extract_expenses(token):
    return extract_paginated("expenses", token)


def extract_categories(token):
    return extract_paginated("categories", token)


def extract_budgets(token):
    return extract_paginated("budgets", token)
