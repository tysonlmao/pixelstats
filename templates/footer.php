<footer class="text-center content">
    <div class="container mt-2 mb-2">
        <div class="row">
            <div class="col-md-6 text-start">
                <p>made by <a target="_blank" rel="noreferrer" href="https://tysonlmao.dev">tysonlmao.dev</a></p>
            </div>
            <div class="col-md-6">
                <p class="text-end">
                    <?php
                    // Executes the git command to get the latest commit hash
                    $commitHash = rtrim(exec('git log --pretty="%h" -n1 HEAD'));

                    // Check if we got a hash back
                    if (!empty($commitHash)) {
                        echo '<a href="https://github.com/tysonlmao/pixelstats/commit/' . $commitHash . '">' . $commitHash . '</a>';
                    } else {
                        echo '<a href="https://github.com/tysonlmao/pixelstats" target="_blank">View source</a>';
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>