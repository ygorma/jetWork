<?php
require_once __DIR__ . '/../../../common/php8_mysql_compat.php';


class buildMaterial
{

    private $Form;
    private $Table;
    public $ObjID;
    private $UploadID = 0;
    private $SubmitID = 0;

    public function __construct() {
      
      if(!isset($GLOBALS["ObjectNumber"])) {

        $GLOBALS["ObjectNumber"] = 0;
        $this->ObjID = $GLOBALS["ObjectNumber"];

      } else {

        $GLOBALS["ObjectNumber"] = $GLOBALS["ObjectNumber"] + 1;
        $this->ObjID = $GLOBALS["ObjectNumber"];

      }
      
    }

    function buildModal(string $modalTitle, string $modalContent, string|NULL $buttonText = NULL, string|NULL $buttonIcon = NULL, array|null $options) {

      $modal = "<a class=\"btn icon-right waves-effect waves-light modal-trigger\" href=\"#$this->ObjID.modal\">$buttonText<i class=\"material-icons right\">$buttonIcon</i></a>

      <!-- Modal Structure -->
      <div id=\"$this->ObjID.modal\" class=\"modal\">
        <div class=\"modal-content\">
          <h4>$modalTitle</h4>
          <p>$modalContent</p>
        </div>
        <div class=\"modal-footer\">";

      $optionNumber = 0;

      foreach($options as $text=>$command) {

        $modal .= "<a id=\"$this->ObjID.modal.button.$optionNumber\" href=\"#!\" class=\"modal-close waves-effect btn-flat\" onclick=\"$command\">$text</a>
        ";

        $optionNumber++;

      }


      $modal .= "<a href=\"#!\" class=\"modal-close waves-effect btn-flat\">Fechar</a>
    </div>";

      $modal .= "</div>    
      ";

      return $modal;

    }



    function buildCard(string $titulo, $imagem, string $conteudo, string ...$submits) {

        // Imprime Card

        if($imagem != NULL) {

          $card = "<div class=\"card\">
          <div class=\"card-image\">
            <img src=\"media/img/$imagem\">
            <span class=\"card-title\">$titulo</span>
          </div>
          <div class=\"card-content\">";



        } else {

          $card = "<div class=\"card\">
              <div class=\"card-content\">
              <span class=\"card-title\">$titulo</span>
          ";

        }

        $card .="      
$conteudo
        </div>
        <div class=\"card-action\">
          ";

        // Imprime Links
        
        foreach ($submits as $submit) {
            $card .= $submit."
          ";
        }

        // Encerra o Card

        $card .= "
        </div>
      </div>";

      echo $card;

    }

    function buildChip($content) {

      return '<div class="chip">'.$content.'</div>';

    }

    function buildFloating(string $icon, string $url, array $options = NULL) {

      return '<div class="fixed-action-btn">
      <a href="?page='.$url.'" class="btn-floating btn-large">
        <i class="large material-icons">'.$icon.'</i>
      </a>
    </div>';

    }

    function buildForm(string $method, string $display = "row") {

        switch($display) {

          case "row":
            $style = "class=\"row\" ";

            break;

          case "inline":
            $style = "style=\"display: inline;\" ";

            break;
          case "inline_block":
            $style = "style=\"display: inline-block;\" ";

            break;
          case "none":
            $style = "style=\"display: none;\" ";

            break;
        }
        
        $formulario = "    <form name=\"$this->ObjID\" id=\"$this->ObjID\" method=\"$method\" $style enctype=\"multipart/form-data\" autocomplete=\"off\">
        
        ";
        $formulario .= $this->Form;
        $formulario .= "
    </form>
    ".(($display == "row")?'<br>':'')."
";

        $this->Form = "";

        return $formulario;

    }

    function formSection($name) {

      $this->Form .= "<h5 class=\"s12\">$name</h5>
      <br>
      ";

    }

    public function inputText(string $name, string $texto, int $gridSize = 12, $required = false, $mask = NULL, $value = NULL, $disabled = false) {

        if($mask == "00/00/0000") {

          $datepicker = 'class="datepicker"';

        } else {

          $datepicker = "";

        }

        $this->Form .= "<div class=\"s$gridSize input-field outlined\" style=\"margin: 5px 5px;\">
            <input id=\"$this->ObjID.$name\" form=\"$this->ObjID\" name=\"$name\" type=\"text\" $datepicker placeholder=\" \" ".(($mask != NULL)?'onfocus="$(this).mask(\''.$mask.'\')"':'')." ".(($required)?'required=""':'')." ".(($disabled)?'disabled':'')." value=\"$value\">
            <label id=\"$this->ObjID.$name.label\" for=\"$this->ObjID.$name\">$texto</label>
        </div>
        ";

    }

    public function inputTextarea(string $name, string|null $texto, int $gridSize = 12, $required = false, $value = NULL, $disabled = false, $richText = false) {

      $this->Form .= "<div class=\"s$gridSize input-field outlined\" ".(($richText)?'':'style="margin: 5px 5px;"').">
          <textarea id=\"$this->ObjID.$name\" form=\"$this->ObjID\" name=\"$name\" placeholder=\" \" ".(($richText)?'class="richText"':'')." ".(($required)?'required=""':'')." ".(($disabled)?'disabled':'')." style=\"min-height: 150px; padding-top: 16px;\">$value</textarea>
          <label id=\"$this->ObjID.$name.label\" for=\"$this->ObjID.$name\">$texto</label>
      </div>
      ";

    }

    public function inputHidden(string $name, string|null $value) {

        if($name == "captcha" && $value == true) {

          $value = constant("CLIENT_KEY");

          $this->Form .= "
        <script src=\"https://www.google.com/recaptcha/api.js?render=$value\"></script>
        
        ";

        }

        $this->Form .= "<input id=\"$this->ObjID.$name\" form=\"$this->ObjID\" name=\"$name\" type=\"hidden\" value=\"$value\" >
        ";

    }

    function inputSelect(string $name, string $texto, int $gridSize = 12, array $options, $required = false, $selected = "unset", bool $returnHTML = false) {

        $element = "<div class=\"input-field outlined s$gridSize\" style=\"margin: 5px 5px;\">
          <select form=\"$this->ObjID\" id=\"$this->ObjID.$name\" name=\"$name\" ".(($required)?'required=""':'').">
              <option disabled selected>Selecione uma Opção</option>
              ";

              foreach($options as $value=>$text) {

                  $element .= "<option value=\"$value\" ".(($value == $selected && $selected != "unset" || $value == "NULL" && $selected == "")?'selected':'').">$text</option>
              ";

              }

                $element .= "
          </select>
          <label for=\"$this->ObjID.$name\">$texto</label>
        </div>
      ";

      return $this->addOrReturn($element, $returnHTML);

    }

    function inputFile($filename, $required = false, $multiple = false) {

      $this->UploadID++;

      if($this->UploadID == 1) {

        // Gera token de Login
        $token = bin2hex(random_bytes(8));

        $this->Form .= "<input form=\"$this->ObjID\" name=\"uploadSession\" type=\"hidden\" value=\"$token\" >
        ";

      }

          $this->Form .= "<div class=\"file-field input-field outlined s12\" style=\"margin: 5px 5px;\">
          <div class=\"btn\" style=\"height: 100%;\">
            <span>Selecione o Arquivo</span>
            <input form=\"$this->ObjID\" type=\"file\" id=\"$this->ObjID.file_$this->UploadID\" ".(($required)?'required=""':'')." name=\"file_$this->UploadID\" onchange=\"uploadFile(this);\" ".(($multiple)?'multiple':'')." ".(($required)?'required=""':'').">
          </div>
          <div class=\"file-path-wrapper\">
            <input id=\"$this->ObjID.file_$this->UploadID.path\" form=\"$this->ObjID\" name=\"$filename\" class=\"file-path\" type=\"text\" placeholder=\" \" value=\"$filename\">
            <div class=\"suffix\"><i class=\"material-icons\" id=\"$this->ObjID.file_$this->UploadID.icon\">file_upload</i></div>
          </div>
        </div>
        ";

    }

    function inputPassword(string $name, string $texto, int $gridSize = 12, bool $returnHTML = false) {

        $element = "<div class=\"s$gridSize input-field outlined\" style=\"margin: 5px 5px;\">
            <input id=\"$this->ObjID.$name\" form=\"$this->ObjID\" type=\"password\" name=\"$name\" class=\"validate\" placeholder=\" \">
            <label for=\"$this->ObjID.$name\">$texto</label>
        </div>
        ";

        return $this->addOrReturn($element, $returnHTML);

    }

    function inputCheckbox(string $name, string|null $texto, int $gridSize = 6, bool $isSet = false, bool $returnHTML = false) {

      // Gera o HTML inicial

      $element = "<label class='s$gridSize'>
      <input id=\"$this->ObjID.$name\" form=\"$this->ObjID\" type=\"checkbox\" name=\"$name\" ".(($isSet)?'checked="checked"':'')."/>
      <span>$texto</span>
    </label>
    ";

      return $this->addOrReturn($element, $returnHTML);

    }

    function inputRadio(string $name, string $texto, int $gridSize = 6, $selected = false, string $value) {

      if($selected == $value) {

        $selected = 'checked="checked"';

      }

      $this->Form .= "<label class='s$gridSize'>
          <input id=\"$this->ObjID.$name\" form=\"$this->ObjID\" type=\"radio\" name=\"$name\" $selected value=\"$value\"/>
          <span>$texto</span>
        </label>
        ";

    }

    function buildFormSubmit(string $type, string $texto, string $action, string $icon = "send", string|NULL $target = NULL) {

      $this->SubmitID++;

      switch($type) {

          case "a":

              return "<a id=\"$this->ObjID.Submit\" href=\"#\" onClick=\"$('#$this->ObjID').submit();\" formaction=\"$action\" form=\"$this->ObjID\">$texto</a>";

              break;
          case "button":

              return "<button id=\"$this->ObjID.Submit.$this->SubmitID\" class=\"btn icon-right waves-effect waves-light\" type=\"submit\" form=\"$this->ObjID\" formaction=\"$action\" ".(($target != NULL)?'formtarget="'.$target.'"':'').">
              $texto<i class=\"material-icons right\">$icon</i>
            </button>";

          case "button_redirect":

            return "<button class=\"btn icon-right waves-effect waves-light\" onclick=\"window.location='$action';\">
            $texto<i class=\"material-icons right\">$icon</i>
          </button>";

              break;
          case "inline_float":

              return "<div class=\"s1\" style=\"margin: 5px 5px; align-content: center; text-align: center;\">
        <button id=\"$this->ObjID.Submit.$this->SubmitID\" type=\"submit\" form=\"$this->ObjID\" formaction=\"$action\" class=\"btn-floating waves-effect waves-light tooltipped\" data-position=\"top\" data-tooltip=\"$texto\" style=\"display: inline-flex;\">
          <i class=\"material-icons\">$icon</i>
        </button>
      </div>
      ";
              break;

      }


    }

    public function inputAutocomplete(string $name, string $texto, int $gridSize = 12, $required = false, array $options, $selected = NULL) {

      $this->Form .= $this->inputHidden($name, $selected);
      $this->Form .= "
        <div class=\"input-field outlined s$gridSize\" style=\"margin: 5px 5px;\">
          <i class=\"material-icons prefix\">search</i>
          <input id=\"$this->ObjID.$name.autocomplete\" name=\"".$name."_text\" type=\"text\" ".(($required)?'required=""':'')." form=\"$this->ObjID\" class=\"autocomplete\">
          <label for=\"$this->ObjID.$name.autocomplete\">$texto</label>
        </div>
        ";

      $optionsArray = "";

      foreach($options as $id=>$text) {

        $optionsArray .= "
                {id: ".$id.", text: \"".$text."\"},";

      }

      $this->Form .= "
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('[id=\"".$this->ObjID.".".$name.".autocomplete\"]');
            var instances = M.Autocomplete.init(elems, {
              // specify options here
              minLength: 0, // shows instantly
              data: [".rtrim($optionsArray, ",")."
              ],
              onAutocomplete: (selectedItems) => {
                if(selectedItems[0] != undefined) {
                  $('[id=\"".$this->ObjID.".".$name."\"]').val(selectedItems[0].id);
                  onAutocompleteSelection(selectedItems[0].id); // Deverá ser definida
                }
              },
              onSearch: (text, autocomplete) => {
                $('[id=\"".$this->ObjID.".".$name.".autocomplete\"]').attr(\"value\",text);
                const filteredData = autocomplete.options.data.filter(item => {
                  return Object.keys(item)
                    .map(key => item[key].toString().toLowerCase().indexOf(text.toLowerCase()) >= 0)
                    .some(isMatch => isMatch);
                });
                autocomplete.setMenuItems(filteredData);
              }
            });
            ";


    // Tratamento da Opção Pré-selecionada
    if($selected != NULL) {

      $this->Form .= "

            var autocomplete = document.querySelector('[id=\"".$this->ObjID.".".$name.".autocomplete\"]');
            var instance = M.Autocomplete.getInstance(autocomplete);
            instance.selectOption($selected);
            
            ";

    }

    $this->Form .= "

          });

        </script>
        
        ";

    }

    function roundedButton($icon, $url, $tooltip, $new_tab = false) {

      return "<a href=\"$url\" style=\"display: inline-flex;\" ".(($new_tab)?'target="_blank"':'')." class=\"btn-floating waves-effect waves-light tooltipped\" data-position=\"top\" data-tooltip=\"$tooltip\">
      <i class=\"material-icons\">$icon</i>
    </a>";

    }

    function addToForm($html) {

      return $this->Form .= $html."
        ";

    }

    function printMasks() {

        return $this->$formMasks;

    }

    function tableSearch(... $columns) {

        $this->Table .= '<div class="row">
        <form class="col s12">
          <div class="row">
            <div class="input-field col s12 outlined">
              <i class="material-icons prefix">search</i>
              <input id="'.$this->ObjID.'.search" type="text" onkeyup="tableSearch('.$this->ObjID.')">
              <label for="icon_prefix2">Procurar</label>
            </div>
          </div>
        </form>
      </div>
      <br>';

    }

    function tableHeading(... $columns) {

        $this->Table .= '<table id="'.$this->ObjID.'" class="striped">
        <thead>
          <tr>
          ';

        foreach($columns as $column) {

            $this->Table .= " <th>$column</th>
          ";

        }

        $this->Table .= '</tr>
        </thead>
        <tbody>      
          ';

    }

    function tableRow(... $columns) {

        $this->Table .= '<tr>
            ';

        foreach($columns as $column) {

            $this->Table .= "<td>$column</td>
            ";

        }

        $this->Table .= '</tr>';

    }

    function tableFooter() {

        $this->Table .= '</tbody>
        </table>';

    }  

    function buildTable() {

       return $this->Table;

    }

    function addOrReturn($HTML, bool $returnMethod) {

      if($returnMethod == true) {

        return $HTML;

      } else {

        $this->Form .= $HTML;

      }

    }

}

?>