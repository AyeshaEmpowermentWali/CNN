<?php
require 'db.php';
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT n.*, c.name AS category_name FROM news_articles n JOIN categories c ON n.category_id = c.id WHERE n.id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);
$related = $pdo->prepare("SELECT * FROM news_articles WHERE category_id = ? AND id != ? LIMIT 3");
$related->execute([$article['category_id'], $article_id]);
$related_articles = $related->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN Clone - <?php echo htmlspecialchars($article['title']); ?></title>
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
        .article { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .article h2 { font-size: 2em; margin-bottom: 20px; color: #d32f2f; }
        .article img { width: 100%; max-height: 400px; object-fit: cover; margin-bottom: 20px; }
        .article p { font-size: 1.1em; line-height: 1.6; margin-bottom: 20px; }
        .related { margin-top: 40px; }
        .related h3 { font-size: 1.5em; margin-bottom: 20px; color: #d32f2f; }
        .related-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .related-item { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .related-item img { width: 100%; height: 100px; object-fit: cover; }
        .related-item h4 { font-size: 1em; padding: 10px; }
        .related-item a { display: block; padding: 10px; text-align: center; background: #d32f2f; color: white; text-decoration: none; }
        @media (max-width: 768px) {
            header h1 { font-size: 1.8em; }
            nav ul { flex-direction: column; text-align: center; }
            nav ul li { margin: 10px 0; }
            .related-grid { grid-template-columns: 1fr; }
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
        <div class="article">
            <h2><?php echo htmlspecialchars($article['title']); ?></h2>
            <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="Article Image">
            <p><?php echo htmlspecialchars($article['content']); ?></p>
        </div>
        <div class="related">
            <h3>Related News</h3>
            <div class="related-grid">
                <?php foreach ($related_articles as $rel): ?>
                    <div class="related-item">
                        <img src="<?php echo htmlspecialchars($rel['thumbnail']); ?>" alt="Thumbnail">
                        <h4><?php echo htmlspecialchars($rel['title']); ?></h4>
                        <a onclick="redirectToArticle(<?php echo $rel['id']; ?>)">Read More</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
