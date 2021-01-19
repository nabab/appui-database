<div class="bbn-db-selections bbn-h-100 bbn-w-100 bbn-content">
  <div class="bbn-margin">
    <div class="bbn-form-full bbn-filter-control">
      <bbn-splitter style="height: 40px; width: 100%; border: 0" class="bbn-w-100" orientation="horizontal">
        <div style="width: 400px; vertical-align: middle; line-height: 40px" class="bbn-c bbn-lg">
          <?=_("Saved queries")?>
          <small>
            <label><input type="radio" value="mine" checked="checked"> Les miennes</label>
            <label><input type="radio" value="all"> Toutes</label>
          </small>
        </div>
        <div>
          <bbn-dropdown class="bbn-lg" placeholder="<?=_("Choose a saved list to view it")?>" :source="liste" :cfg="{dataTextField:'text', dataValueField: 'id', }" style="width: 100%" v-model="currentFilter"></bbn-dropdown>
        </div>
        <div style="width: 200px; vertical-align: middle" class="bbn-r bbn-lg">
          <bbn-button icon="nf nf-fa-calculator" @click="count()" style="background-color: #AC0606; color: #EEE"></bbn-button>
          <bbn-button icon="nf nf-fa-eye" @click="build_table()" style="background-color: #06A6A8; color: #EEE"></bbn-button>
          <bbn-button icon="nf nf-fa-hand_right" @click="open()" style="background-color: #FF9900; color: #EEE"></bbn-button>
          <bbn-button icon="nf nf-fa-save" @click="save()" style="background-color: #00BD00; color: #EEE"></bbn-button>
        </div>
      </bbn-splitter>
    </div>
    <div class="bbn-form-full bbn-filter-control">
      <h3><?=_("See the following fields")?></h3>
      <bbn-multiselect ref="aa" :sortable="true" class="bbn-lg" :source="fields" name="select[]" placeholder="<?=_("Choose the fields you want to appear")?>" v-model="columns"></bbn-multiselect>
    </div>
    <appui-queries-filter-control :fields="fields" :conditions="conditions" :concat="concat" ref="filter" :num="num" :first="true"></appui-queries-filter-control>
    <div class="bbn-form-full">
      <code style="white-space: pre" v-html="JSON.stringify(conditions, true, 2)"></code>
    </div>
    <div class="bbn-form-full">
      <code style="white-space: pre" v-html="JSON.stringify(columns, true, 2)"></code>
    </div>
    <div class="bbn-form-full">
      <code style="white-space: pre" v-html="JSON.stringify(fields2, true, 2)"></code>
    </div>
  </div>
  <div class="bbn-100 bbn-content bbn-widget bbn-flex-height" style="position: absolute; top: 0px; left: 0px; display: none" ref="tcontainer">
    <div class="bbn-form-full">
      <bbn-button icon="nf nf-fa-caret_square_left" @click="to_list()"></bbn-button>
    </div>
    <div class="bbn-w-100 bbn-flex-fill">
      <bbn-table class="bbn-100" ref="table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>eMail</th>
              <th>Tel</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Thomas</td>
              <td>t@bbn.so</td>
              <td>324 802 2951</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Mirko</td>
              <td>m@bbn.so</td>
              <td>329 333 3333</td>
            </tr>
            <tr>
              <td>3</td>
              <td>Loredana</td>
              <td>l@bbn.so</td>
              <td>324 444 4444</td>
            </tr>
            <tr>
              <td>4</td>
              <td>Vito</td>
              <td>v@bbn.so</td>
              <td>324 555 5555</td>
            </tr>
          </tbody>
        </table>
      </bbn-table>
    </div>
  </div>
</div>
<?php
/* Here is how to generate the select strings based on kendo from the console

        <numeric v-if="type === 'number'"min="1" max="5"></numeric>
        <input v-if="type === 'string'" :disabled="operator ? null : 'disabled'" v-bind="value">


var st = '';
for ( var n in kendo.ui.FilterCell.fn.options.operators ){
  st += "\n" + '<script type="text/html" id="appui_' + n + '_operator">' + "\n" + '  <select name="operator" class="k-input">' + "\n";
  for ( var m in kendo.ui.FilterCell.fn.options.operators[n] ){
    st += '    <option value="' + m + '"><?=_("' + kendo.ui.FilterCell.fn.options.operators[n][m] + '")?></option>' + "\n";
  }
  st +=  '  </select>' + "\n" + '</script>';
}
bbn.fn.log(st);
alert(st);

*/
?>