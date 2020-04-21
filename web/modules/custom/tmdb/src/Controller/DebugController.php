<?php

namespace Drupal\tmdb\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;

/**
 * Class DebugController.
 */
class DebugController extends ControllerBase {

  /**
   * Debugger page contents.
   *
   * @return string
   *   Return the debug page contents.
   */
  public function contents() {

    $movies = \Drupal::service('tmdb.client')->getMoviesFromList();
    foreach ($movies as $movie) {
      $data = \Drupal::service('tmdb.client')->fetchMovie($movie['id']);
      if(isset($data["genres"])) {
        foreach ($data["genres"] as $genre) {
          $terms = \Drupal::entityTypeManager()
            ->getStorage('taxonomy_term')
            ->loadByProperties(['field_tmdb_id' => $genre['id']]);
          if (!count($terms)) {
            $entity = \Drupal\taxonomy\Entity\Term::create([
              'name' => $genre['name'], 
              'vid' => 'genre',
            ]);
            $entity->set('field_tmdb_id',   $genre['id']);
            $entity->save();
          }
        }
      }
    }

    // $movies = \Drupal::service('tmdb.client')->getMoviesFromList();
    // foreach ($movies as $movie) {
      // $nodes = \Drupal::entityTypeManager()
      //   ->getStorage('node')
      //   ->loadByProperties(['field_tmdb_id' => $movie['id']]);
      // $node = reset($nodes);
      // if ($node->id()) {
        // $node->set('field_banner_path', $movie['backdrop_path']);
        // $node->set('field_poster_path', $movie['poster_path']);
        // $node->set('field_release_date', $movie['release_date']);
        // $node->set('field_synopsis', array(
        //   'value' => $movie['overview'],
        //   'format' => 'basic_html',
        //   ));
        // $node->save();
    //   }
    // }

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Lil help?'),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}