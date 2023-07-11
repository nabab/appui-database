<div>
  <bbn-table :source="root + 'data/data'"
             ref="table"
             :toolbar="[{text: _('New row'), action: 'insert'}]"
             :pageable="true"
             :sortable="true"
             :filterable="true"
             :showable="true"
             :editable="selector ? false : 'popup'"
             :full-storage-name="myStorageName"
             :columns="columns"
             :url="root + 'actions/data/update'"
             :data="{
               db: source.db,
               host: source.host,
               table: source.table,
               engine: source.engine
             }"
             @click-row="clickRow"
             @editSuccess="success"
  >
  </bbn-table>
  <input type="text" ref="copyUid" style="opacity:0;position:absolute" class="test">
</div>
