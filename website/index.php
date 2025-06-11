<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 2em; background-color: #f4f7f6; color: #333; }
        .container { max-width: 800px; margin: auto; padding: 2em; background-color: #fff; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #005a9c; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5em; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
        .error { color: #d93025; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Application POZOS</h1>
        <p>Cliquez sur le bouton pour afficher la liste des étudiants via l'API.</p>
        <form method="post">
            <button type="submit" name="list_students">List Student</button>
        </form>

        <?php
        if (isset($_POST['list_students'])) {
            $url = 'http://api:5000/pozos/api/v1.0/get_student_ages';
            $user = 'toto';
            $pass = 'python';

            $options = [
                'http' => [
                    'header'  => "Authorization: Basic " . base64_encode("$user:$pass"),
                    'method'  => 'GET'
                ]
            ];
            $context  = stream_context_create($options);
            
            $result = @file_get_contents($url, false, $context);

            if ($result === FALSE) {
                echo "<p class='error'>Erreur : Impossible de contacter l'API. Vérifiez que les conteneurs sont bien lancés et communiquent.</p>";
            } else {
                $students = json_decode($result, true);
                if (isset($students['student_ages'])) {
                    echo "<h3>Liste des étudiants :</h3>";
                    echo "<table><tr><th>Nom</th><th>Âge</th></tr>";
                    foreach ($students['student_ages'] as $student) {
                        echo "<tr><td>" . htmlspecialchars($student['name']) . "</td><td>" . htmlspecialchars($student['age']) . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                     echo "<p class='error'>L'API a répondu, mais le format des données est incorrect.</p>";
                }
            }
        }
        ?>
    </div>
</body>
</html>