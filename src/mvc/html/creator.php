<form class="bbn-padded">
  <h1 v-text="_('Query builder')" class="bbn-c"></h1>
  <div class="bbn-form-label">Main table for the query</div>
  <div class="bbn-form-field">
    <bbn-dropdown name="id_table"
                  style="width: 100%"
                  :placeholder="_('Choose')"
                  :source="source.tables"
                  v-model="id_table"
    ></bbn-dropdown>
  </div>
  <div class="bbn-form-label" v-if="linkedTables.length">Link tables</div>
  <div class="bbn-form-field" v-if="linkedTables.length">
    <bbn-multiselect name="tables[]"
                  :placeholder="_('Choose')"
                  :source="linkedTables"
                  v-model="tables"
    ></bbn-multiselect>
  </div>
</form>