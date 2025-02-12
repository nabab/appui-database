<div class="bbn-overlay bbn-flex-height appui-database-sync-conflicts">
  <div class="bbn-header bbn-spadding bbn-flex-width bbn-vmiddle">
    <div class="bbn-flex-fill bbn-vmiddle">
      <span><?= _('Tables') ?>:</span>
      <bbn-dropdown :source="tables"
                    bbn-model="currentTable"
                    class="bbn-hsmargin"
                    ref="tablesList"
                    :placeholder="!tables.length ? (!lastReception ? _('Loading') + '...' : _('No conflicts found')) : _('Select a table')"/>
      <bbn-button bbn-if="currentTable"
                  icon="nf nf-fa-refresh"
                  label="<?= _('Refresh') ?>"
                  :notext="true"
                  @click="refreshFile"/>
      <i bbn-if="currentTableDate"
         class="nf nf-fa-calendar bbn-right-xsspace bbn-left-sspace"/>
      <span bbn-if="currentTableDate"
            bbn-text="currentTableDate"/>
      <i bbn-if="currentTableTime"
         class="nf nf-oct-clock bbn-right-xsspace bbn-left-sspace"/>
      <span bbn-if="currentTableTime"
            bbn-text="currentTableTime"/>
    </div>
    <div class="bbn-vmiddle">
      <span bbn-if="lastReception"><?= _('Tables list loaded on') ?></span>
      <span bbn-else><?= _('Tables not yet loaded') ?></span>
      <i bbn-if="currentLastReceptionDate"
         class="nf nf-fa-calendar bbn-right-xsspace bbn-left-sspace"/>
      <span bbn-if="currentLastReceptionDate"
            bbn-text="currentLastReceptionDate"/>
      <i bbn-if="currentLastReceptionTime"
         class="nf nf-oct-clock bbn-right-xsspace bbn-left-sspace"/>
      <span bbn-if="currentLastReceptionTime"
            bbn-text="currentLastReceptionTime"/>
    </div>
  </div>
  <div class="bbn-flex-fill">
    <bbn-table bbn-if="tableVisible"
               :source="root + 'data/sync/conflicts'"
               ref="table"
               :pageable="true"
               :sortable="true"
               :filterable="true"
               :data="{file: currentFile}"
               :selection="true"
               uid="id"
               :toolbar="[{
                  text: '<?= _('Fix') ?>',
                  icon: 'nf nf-md-auto_fix',
                  action: fixSelected,
                  disabled: !selected.length
                }, {
                  text: '<?= _('Fix All') ?>',
                  icon: 'nf nf-fa-magic',
                  action: fixAll,
                  disabled: !currentTableTotal
                }, {
                  text: '<?= _('Delete') ?>',
                  icon: 'nf nf-fa-trash',
                  action: removeSelected,
                  disabled: !selected.length
                }]"
                @hook:mounted="setWatch">
      <bbns-column field="id"
                   label="ID"
                   :render="renderJSON"/>
      <bbns-column bbn-for="(db, i) in source.dbs"
                   :field="db"
                   :label="db"
                   :key="i"
                   :component="$options.components.compare"
                   :width="150"
                   cls="bbn-c"
                   :options="{
                     field: db
                   }"/>
      <bbns-column :buttons="[{
                     text: '<?= _('Fix') ?>',
                     icon: 'nf nf-md-auto_fix',
                     notext: true,
                     action: fix
                   }, {
                     text: '<?= _('Delete') ?>',
                     icon: 'nf nf-fa-trash',
                     notext: true,
                     action: removeItem
                   }]"
                   :width="100"
                   cls="bbn-c"/>
    </bbn-table>
    <div bbn-else-if="!currentTable"
         class="bbn-overlay bbn-middle">
      <div class="bbn-xl bbn-b bbn-vmiddle">
        <i class="bbn-right-sspace nf nf-md-subdirectory_arrow_left"
           style="transform: rotate(90deg)"/>
        <span><?= _('SELECT A TABLE') ?></span>
      </div>
    </div>
  </div>
</div>