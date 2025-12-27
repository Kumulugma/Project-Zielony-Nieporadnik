<section class="space-ptb bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h3 class="mb-4">O tym dzienniku</h3>
                <p class="mb-3">
                    Kolekcja zaczęła się od kilku sukulentów. Potem przyszły kaktusy, szczawiki, 
                    chryzantemy... i w pewnym momencie straciłem rachubę. Postanowiłem wszystko 
                    uporządkować i stworzyć cyfrowy dziennik.
                </p>
                <p class="mb-3">
                    Każda roślina ma tutaj swoją stronę z nazwą łacińską, zdjęciami i krótkimi 
                    notatkami. Możesz przeglądać je według grup albo po prostu zobaczyć co dodałem 
                    ostatnio. Z czasem pojawia się też historia - jak roślina wygląda wiosną, latem, 
                    po przesadzeniu.
                </p>
                <div class="row mt-5">
                    <?php
                    $stats = array(
                        array(
                            'number' => wp_count_posts('plant')->publish,
                            'label' => 'roślin w katalogu'
                        ),
                        array(
                            'number' => wp_count_posts('post')->publish,
                            'label' => 'wpisów na blogu'
                        ),
                        array(
                            'number' => count(get_terms(array('taxonomy' => 'plant-group', 'hide_empty' => true))),
                            'label' => 'grup roślin'
                        )
                    );
                    
                    foreach ($stats as $stat):
                    ?>
                        <div class="col-md-4 text-center mb-3">
                            <div class="p-3">
                                <div class="h2 mb-2" style="color: #719367;"><?php echo $stat['number']; ?></div>
                                <div class="small text-muted text-uppercase"><?php echo $stat['label']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>