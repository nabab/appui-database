<div class="bbn-overlay">
  <bbn-table :source="tableSource"
             :sortable="true"
             :filterable="true"
             :info="true"
             button-mode="menu"
             :columns= "columns"
             :toolbar="['insert']"
             editor="appui-database-column-editor"
             editable="popup"
             :editor-options="editorOptions"
             ref="table"
             >
  </bbn-table>
</div>