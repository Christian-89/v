<?php
// Conexión a la base de datos (ejemplo con MySQL)
$host = "localhost";
$dbname = "recetas_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

// Obtener los datos del formulario
$sexo = $_POST['sexo'];
$edad = $_POST['edad'];
$peso = $_POST['peso'];
$estatura = $_POST['estatura'];
$actividad = $_POST['actividad'];
$enfermedad = isset($_POST['enfermedad']) ? $_POST['enfermedad'] : '';
$objetivo = isset($_POST['objetivo']) ? $_POST['objetivo'] : '';

// Consultar recetas según las condiciones
$query = "SELECT * FROM recetas WHERE 1=1";

// Filtrar por enfermedades
if ($enfermedad == 'Diabetes') {
    $query .= " AND apto_para_diabetes = 1";
} elseif ($enfermedad == 'Colesterol') {
    $query .= " AND apto_para_colesterol = 1";
} elseif ($enfermedad == 'Hipertensión') {
    $query .= " AND apto_para_hipertension = 1";
}

// Filtrar por objetivo
if (!empty($objetivo)) {
    $query .= " AND objetivo = :objetivo";
}

$stmt = $conn->prepare($query);
if (!empty($objetivo)) {
    $stmt->bindParam(':objetivo', $objetivo);
}
$stmt->execute();
$recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar las recetas encontradas
if (count($recetas) > 0) {
    echo "<h1>Recetas recomendadas</h1>";
    foreach ($recetas as $receta) {
        echo "<h2>" . htmlspecialchars($receta['nombre']) . "</h2>";
        echo "<p>" . htmlspecialchars($receta['descripcion']) . "</p>";
        echo "<p><strong>Calorías:</strong> " . $receta['calorias'] . "</p>";
        echo "<p><strong>Proteínas:</strong> " . $receta['proteinas'] . "g</p>";
        echo "<p><strong>Carbohidratos:</strong> " . $receta['carbohidratos'] . "g</p>";
        echo "<p><strong>Grasas:</strong> " . $receta['grasas'] . "g</p>";
        echo "<hr>";
    }
} else {
    echo "<h1>No se encontraron recetas que cumplan con los criterios.</h1>";
}
?>

