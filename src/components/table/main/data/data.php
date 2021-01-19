<div>
  <bbn-table :source="root + 'data/data'"
             ref="table"
             :pageable="true"
             :sortable="true"
             :filterable="true"
             :showable="true"
             editable="popup"
             :columns="columns"
             :url="root + 'actions/data/update'"
             :data="{
               db: source.db,
               host: source.host,
               table: source.table,
               engine: source.engine
             }"
             @editSuccess="success"
  >
  </bbn-table>
  <input type="text" ref="copyUid" style="opacity:0;position:absolute" class="test">
</div>