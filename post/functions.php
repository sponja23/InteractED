<?php
function addHorizontalCard($ID, $Path, $Title, $Creator) {
    echo '<div class="col s12">
              <div class="card horizontal hoverable item" id="' . md5($ID) . '">
                  <div class="card-image valign-wrapper" style="width: 192px; height: 108px;">
                      <img src="' . $Path . '" style="display: block; margin: 0 auto;">
                  </div>
                  <div class="card-stacked">
                      <div class="card-content">
                          <strong>' . $Title . '</strong>
                          <p>' . $Creator . '</p>
                      </div>
                  </div>
              </div>
          </div>';
}

function addVerticalCard($ID, $Path, $Title, $Creator) {
    echo '<div class="col s12 m3">
              <div class="card hoverable item" id="' . md5($ID) . '">
                  <div class="card-image valign-wrapper" style="height: 100px;">
                      <img src="' . $Path . '" style="width: initial !important; max-width: 100%; max-height: 100%; margin: 0 auto;">
                  </div>
                  <div class="card-content">
                      <strong>' . $Title . '</strong>
                      <p>' . $Creator . '</p>
                  </div>
              </div>
          </div>';
}
?>