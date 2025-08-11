<div>
  <bbn-table :source="root + 'data/data'"
             ref="table"
             :toolbar="[{label: _('New row'), action: 'insert'}]"
             :pageable="true"
             :sortable="true"
             :filterable="true"
             :showable="true"
             :editable="selector ? false : 'popup'"
             :full-storage-name="myStorageName"
             :columns="columns"
             :url="root + 'actions/data/update'"
             :data="{
               db: source.database,
               host: source.id_host,
               table: source.name,
               engine: source.engine
             }"
             @click-row="clickRow"
             @editsuccess="success">
  </bbn-table>
  <input type="text" ref="copyUid" style="opacity:0;position:absolute" class="test">
</div>
