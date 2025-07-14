<?php
require 'db.php';
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT n.*, c.name AS category_name FROM news_articles n JOIN categories c ON n.category_id = c.id WHERE n.category_id = ?");
$stmt->execute([$category_id]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
$category = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
$category->execute([$category_id]);
$category_name = $category->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN Clone - <?php echo htmlspecialchars($category_name); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f5f5f5; color: #333; }
        header { background: #d32f2f; color: white; padding: 20px; text-align: center; }
        header h1 { font-size: 2.5em; }
        nav { background: #333; padding: 10px; }
        nav ul { list-style: none; display: flex; justify-content: center; }
        nav ul li { margin: 0 15px; }
        nav ul li a { color: white; text-decoration: none; font-size: 1.2em; cursor: pointer; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .category-section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .category-section h2 { font-size: 1.8em; margin-bottom: 20px; color: #d32f2f; }
        .news-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .news-item { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .news-item img { width: 100%; height: 150px; object-fit: cover; }
        .news-item h3 { font-size: 1.2em; padding: 10px; }
        .news-item p { padding: 0 10px 10px; font-size: 0.9em; color: #555; }
        .news-item a { display: block; padding: 10px; text-align: center; background: #d32f2f; color: white; text-decoration: none; }
        @media (max-width: 768px) {
            header h1 { font-size: 1.8em; }
            nav ul { flex-direction: column; text-align: center; }
            nav ul li { margin: 10px 0; }
            .news-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header>
        <h1>CNN News</h1>
    </header>
    <nav>
        <ul>
            <?php
            $categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($categories as $cat): ?>
                <li><a onclick="redirectToCategory(<?php echo $cat['id']; ?>)"><?php echo htmlspecialchars($cat['name']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="container">
        <div class="category-section">
            <h2><?php echo htmlspecialchars($category_name); ?> News</h2>
            <div class="news-grid">
                <?php foreach ($articles as $article): ?>
                    <div class="news-item">
                        <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="Thumbnail">
                        <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                        <p><?php echo htmlspecialchars($article['short_description']); ?></p>
                        <a onclick="redirectToArticle(<?php echo $article['id']; ?>)">Read More</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
