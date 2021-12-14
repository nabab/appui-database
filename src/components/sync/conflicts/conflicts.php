<div class="bbn-overlay bbn-flex-height appui-database-sync-conflicts">
  <div class="bbn-header bbn-spadded bbn-flex-width bbn-vmiddle">
    <div class="bbn-flex-fill bbn-vmiddle">
      <span><?=_('Tables')?>:</span>
      <bbn-dropdown :source="tables"
                    v-model="currentTable"
                    class="bbn-hsmargin"
                    ref="tablesList"
                    :placeholder="!tables.length ? (!lastReception ? _('Loading...') : _('No conflicts found')) : _('Select a table')"/>
      <bbn-button v-if="currentTable"
                  icon="nf nf-fa-refresh"
                  text="<?=_('Refresh')?>"
                  :notext="true"
                  @click="refreshFile"/>
      <i v-if="currentTableDate"
         class="nf nf-fa-calendar bbn-right-xspace bbn-left-sspace"/>
      <span v-if="currentTableDate"
            v-text="currentTableDate"/>
      <i v-if="currentTableTime"
         class="nf nf-oct-clock bbn-right-xspace bbn-left-sspace"/>
      <span v-if="currentTableTime"
            v-text="currentTableTime"/>
    </div>
    <div class="bbn-vmiddle">
      <span v-if="lastReception"><?=_('Tables list loaded on')?></span>
      <span v-else><?=_('Tables not yet loaded')?></span>
      <i v-if="currentLastReceptionDate"
         class="nf nf-fa-calendar bbn-right-xspace bbn-left-sspace"/>
      <span v-if="currentLastReceptionDate"
            v-text="currentLastReceptionDate"/>
      <i v-if="currentLastReceptionTime"
         class="nf nf-oct-clock bbn-right-xspace bbn-left-sspace"/>
      <span v-if="currentLastReceptionTime"
            v-text="currentLastReceptionTime"/>
    </div>
  </div>
  <div class="bbn-flex-fill">
    <bbn-table v-if="tableVisible"
               :source="root + 'data/sync/conflicts'"
               ref="table"
               :pageable="true"
               :data="{file: currentFile}"
               :selection="true"
               uid="id"
               :toolbar="[{
                  text: '<?=_('Fix')?>',
                  icon: 'nf nf-mdi-auto_fix',
                  action: fixSelected,
                  disabled: !selected.length
                }, {
                  text: '<?=_('Fix All')?>',
                  icon: 'nf nf-fa-magic',
                  action: fixAll,
                  disabled: !currentTableTotal
                }, {
                  text: '<?=_('Delete')?>',
                  icon: 'nf nf-fa-trash',
                  action: removeSelected,
                  disabled: !selected.length
                }]"
                @hook:mounted="setWatch">
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
                     text: '<?=_('Fix')?>',
                     icon: 'nf nf-mdi-auto_fix',
                     notext: true,
                     action: fix
                   }, {
                     text: '<?=_('Delete')?>',
                     icon: 'nf nf-fa-trash',
                     notext: true,
                     action: remove
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