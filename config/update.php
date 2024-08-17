<?php

include_once "../utils/config.php";

if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

    // Fetch existing file details
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->execute([$fileId]);
    $file = $stmt->fetch();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fileName = $_POST['nomFichier'];
        $fileDescription = $_POST['descriptionFichier'];
        $fileLevel = $_POST['niveau'];
        
        // Update file details in the database
        $stmt = $pdo->prepare("UPDATE files SET name = ?, description = ?, level = ? WHERE id = ?");
        if ($stmt->execute([$fileName, $fileDescription, $fileLevel, $fileId])) {
            header('Location: index.php');
            exit();
        } else {
            $error = "Failed to update file information in the database.";
        }
    }
} else {
    header('Location: ../../admin/admin.php');
    exit();
}
?>

<?php require_once '../partial/link.php';?>
<body>
    <header>
        <div class="logo">COSMOS X DOC - Admin</div>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Gestion des fichiers</a></li>
            <li><a href="index.php#gestion-utilisateurs">Gestion des utilisateurs</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Modifier un fichier</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form id="updateForm" method="POST" action="update.php?id=<?= htmlspecialchars($fileId) ?>">
                <input type="text" id="nomFichier" name="nomFichier" placeholder="Nom du fichier" value="<?= htmlspecialchars($file['name']) ?>" required>
                <textarea id="descriptionFichier" name="descriptionFichier" placeholder="Description du fichier" required><?= htmlspecialchars($file['description']) ?></textarea>
                <div class="niveau">
                    <label><input type="radio" name="niveau" value="licence1" <?= $file['level'] == 'licence1' ? 'checked' : '' ?>> Licence 1</label>
                    <label><input type="radio" name="niveau" value="licence2" <?= $file['level'] == 'licence2' ? 'checked' : '' ?>> Licence 2</label>
                    <label><input type="radio" name="niveau" value="licence3" <?= $file['level'] == 'licence3' ? 'checked' : '' ?>> Licence 3</label>
                    <label><input type="radio" name="niveau" value="master1" <?= $file['level'] == 'master1' ? 'checked' : '' ?>> Master 1</label>
                    <label><input type="radio" name="niveau" value="master2" <?= $file['level'] == 'master2' ? 'checked' : '' ?>> Master 2</label>
                </div>
                <button type="submit">Modifier</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 COSMOS X DOC. Tous droits réservés.</p>
    </footer>
</body>
</html>
