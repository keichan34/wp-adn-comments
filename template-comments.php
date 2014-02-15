<?php
$config = array(
  'comments_url' => get_permalink()
);

$adn_username = get_the_author_meta('adn');

if (!empty($adn_username)) {
  $adn_username = preg_replace('/^@/', '', $adn_username);
  $config['default_at_reply'] = $adn_username;
}

?>
<div id="comments" class="comments-area">
  <script>
    var ADN_COMMENTS_CONFIG = <?php echo json_encode($config); ?>;
  </script>
  <script async src="<?php echo ADN_Comments::embed_file_url(); ?>" id='adn-comments'></script>
</div>
