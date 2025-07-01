<div class="appui-database-db bbn-overlay">
  <div bbn-if="!ready"
       class="bbn-overlay bbn-middle">
    <bbn-loader/>
  </div>
  <div bbn-else-if="currentData?.error"
       class="bbn-overlay bbn-middle">
    <div class="bbn-block bbn-padding bbn-shadow bbn-state-error bbn-lg bbn-xlmargin"
         bbn-text="currentData.error"/>
  </div>
  <div bbn-else
       class="bbn-overlay bbn-flex-height">
    <bbn-toolbar :source="toolbar"
                 class="bbn-no-border bbn-spadding"/>
    <bbn-splitter :orientation="orientation"
                  :resizable="false"
                  :collapsible="true"
                  class="bbn-flex-fill">
      <bbn-pane :resizable="true"
                :size="isHorizontal ? 250 : 'max-content'">
        <div class="bbn- bbn-smargin bbn-secondary-border bbn-radius"
             style="box-shadow: 1px 1px 0.15rem var(--shadow-box)">
          <div class="bbn-secondary bbn-c bbn-xspadding bbn-upper bbn-b"><?=_("Information")?></div>
          <div class="bbn-spadding bbn-c bbn-grid-fields">
            <template bbn-for="c in currentInfo"
                      class="appui-database-db-info-item">
              <div class="bbn-upper bbn-secondary-text-alt bbn-b bbn-label"
                   bbn-text="c.text"/>
              <div class="bbn-light"
                   bbn-text="c.value"/>
            </template>
          </div>
        </div>
      </bbn-pane>
      <bbn-pane :resizable="true">
        <div class="bbn-overlay bbn-spadding bbn-radius">
          <div class="bbn-100 bbn-radius"
               style="box-shadow: 1px 1px 0.15rem var(--shadow-box)">
            <bbn-table ref="table"
                      @toggle="onTableToggle"
                      @dataloaded="clearTableSelection"
                      :source="root + 'data/tables/' + currentData.engine + '/' + currentData.host + '/' + currentData.name"
                      :data="{
                        host_id: currentData.id,
                        engine: currentData.engine
                      }"
                      :selection="true"
                      :pageable="true"
                      :showable="true"
                      :limit="50"
                      :info="true"
                      uid="name"
                      class="bbn-radius"
                      :scrollable="true"
                      style="border-color: var(--header-background)"
                      button-mode="menu">
              <bbns-column field="name"
                           label="<?= _('Database') ?>"
                           component="appui-database-table-columns-cell"/>
              <bbns-column field="is_real"
                           label="<i class='nf nf-cod-database'></i>"
                           full-label="<?= _("Exists in the host") ?>"
                           :render="renderRealVirtual"
                           :width="30"
                           cls="bbn-c"/>
              <bbns-column field="is_virtual"
                           label="<i class='nf nf-md-opera'></i>"
                           full-label="<?= _("Exists as options") ?>"
                           :render="renderRealVirtual"
                           :width="30"
                           cls="bbn-c"/>
              <bbns-column label="<?= _('Charset') ?>"
                           field="charset"
                           :width="120"
                           cls="bbn-c"/>
              <bbns-column bbn-if="hasCollation"
                           label="<?= _('Collation') ?>"
                           field="collation"
                           :width="150"
                           cls="bbn-c"/>
              <bbns-column label="<?= _('Size') ?>"
                           field="size"
                           :render="row => formatBytes(row.size)"
                           :width="100"
                           cls="bbn-c"/>
              <bbns-column :buttons="getTableButtons"
                           :width="30"
                           cls="bbn-c"/>
            </bbn-table>
          </div>
        </div>
      </bbn-pane>
    </bbn-splitter>
  </div>
</div>
