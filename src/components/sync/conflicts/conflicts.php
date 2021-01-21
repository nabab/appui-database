<div class="bbn-overlay bbn-flex-height appui-database-sync-conflicts">
  <div class="bbn-header bbn-spadded">
    <span><?=_('Tables')?>:</span>
    <bbn-dropdown :source="tables"
                  v-model="currentTable"
                  class="bbn-hsmargin"
                  ref="tablesList">
    </bbn-dropdown>
    <i v-if="currentTableDate"
       class="nf nf-fa-calendar bbn-right-xspace"/>
    <span v-if="currentTableDate"
          v-text="currentTableDate"/>
    <i v-if="currentTableTime"
       class="nf nf-oct-clock bbn-right-xspace bbn-left-sspace"/>
    <span v-if="currentTableTime"
          v-text="currentTableTime"/>
  </div>
  <div class="bbn-flex-fill">
    <bbn-table v-if="tableVisible"
               :source="root + 'data/sync/conflicts'"
               ref="table"
               :pageable="true"
               :data="{file: currentFile}">
      <bbns-column field="id"
                   title="ID"/>
      <bbns-column v-for="(db, i) in source.dbs"
                   :field="db"
                   :title="db"
                   :key="i"
                   :component="$options.components.compare"
                   :width="150"
                   cls="bbn-c"
                   :options="{
                     field: db
                   }"/>
      <bbns-column :buttons="[{
                     text: '<?=_('Delete')?>',
                     icon: 'nf nf-fa-trash',
                     notext: true
                   }]"
                   :width="100"
                   cls="bbn-c"/>
    </bbn-table>
    <div v-else-if="!currentTable"
         class="bbn-overlay bbn-middle">
      <div class="bbn-xl bbn-b bbn-vmiddle">
        <i class="bbn-right-sspace nf nf-mdi-subdirectory_arrow_left"
           style="transform: rotate(90deg)"/>
        <span><?=_('SELECT A TABLE')?></span>
      </div>
    </div>
  </div>
</div>