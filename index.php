<?php
require_once "config.php";

$content = 'shows/show_list.php';
$title = 'Show List';

if (isset($_GET['page'])) {
    $page = strip_tags($_GET['page']);

    switch ($page) {
        case '':
        case 'show-list':
            break;

        case 'show-create':
            $title = 'Create A Show';
            $content = 'shows/show_create.php';
            break;

        case 'show-delete':
            $title = 'Create A Show';
            $content = 'shows/show_delete.php';
            break;

        case 'season-list':
            $title = 'Season List';
            $content = 'seasons/season_list.php';
            break;
        case 'season-view':
            $title = 'View A Season';
            $content = 'seasons/season_view.php';
            break;

        case 'season-create':
            $title = 'Create A Season';
            $content = 'seasons/season_create.php';
            break;

        case 'season-delete':
            $title = 'Delete A Season';
            $content = 'seasons/season_delete.php';
            break;

        case 'reset':
            $title = 'Reset Database';
            $content = 'reset.php';
            break;
        default:
            $content = 'view/404.php';
            $title = 'Page Doesn\'t Exist';
            break;
    }
}


include_once('view/header.php');
?>
<div class="wrapper">
    <?php include_once($content); ?>
</div>
<?php
include_once('view/footer.php');
