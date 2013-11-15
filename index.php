<? require __DIR__ . '/handler.php' ?>
<!doctype html>
<meta charset="utf-8">
<title>Archived Google Reader Feeds</title>
<style>body { font-family: sans-serif; font-size: 12px; }</style>

<form>
    <label>Feed URL prefix: <input type="text" size="50" name="prefix" placeholder="http://example.com/…"
        value="<?= htmlspecialchars($prefix, ENT_QUOTES, 'UTF-8'); ?>"></label>
    <button type="submit">Search</button>
</form>

<? if ($prefix): ?>
    <? if (empty($results)): ?>
        <div class="error">No matching feeds found - try a different prefix.</div>
    <? else: ?>
        <form>
            <? foreach ($results as $result): ?>
                <? $data = array_combine($fields, $result); ?>
                <? $url = rawurldecode(preg_replace('#^https://www.google.com/reader/api/0/stream/contents/feed/#', '', $data['original'])); ?>
                <? $hurl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>
                <div>
                    <label><input type="radio" name="feed" value="<?= $hurl ?>"> <?= $hurl ?></label>
                </div>
            <? endforeach; ?>
            <button type="submit">Fetch archived JSON feed</button>
        </form>
    <? endif; ?>
<? endif; ?>
