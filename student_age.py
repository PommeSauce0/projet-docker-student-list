from flask import Flask, jsonify, request
from functools import wraps
import json

app = Flask(__name__)

def check_auth(username, password):
    """Vérifie si le nom d'utilisateur et le mot de passe sont corrects."""
    return username == 'toto' and password == 'python'

def requires_auth(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        auth = request.authorization
        if not auth or not check_auth(auth.username, auth.password):
            return 'Accès non autorisé', 401
        return f(*args, **kwargs)
    return decorated

@app.route('/pozos/api/v1.0/get_student_ages', methods=['GET'])
@requires_auth
def get_student_ages():
    try:
        with open('/data/student_age.json', 'r') as f:
            data = json.load(f)
        return jsonify({'student_ages': data})
    except FileNotFoundError:
        return jsonify({"erreur": "Fichier de données introuvable"}), 404
    except Exception as e:
        return jsonify({"erreur": str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)