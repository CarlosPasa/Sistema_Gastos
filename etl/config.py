import os

from dotenv import load_dotenv

load_dotenv()

API_URL = os.getenv("API_URL")
EMAIL = os.getenv("EMAIL")
PASSWORD = os.getenv("PASSWORD")
