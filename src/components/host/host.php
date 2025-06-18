<div class="appui-database-host bbn-overlay">
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
                <div>
        <div class="bbn-m bbn-smargin bbn-secondary-border bbn-radius"
             style="box-shadow: 1px 1px 0.15rem var(--shadow-box)">
          <!--<div class="bbn-header bbn-spadding">
            <bbn-button icon="nf nf-md-export"
                        :notext="true"
                        @click="exportDb"/>
          </div>-->
          <div class="bbn-secondary bbn-c bbn-xspadding bbn-upper bbn-b"><?=_("Information")?></div>
          <div class="bbn-spadding bbn-c bbn-flex-column"
               style="gap: var(--space)">
            <div :class="['appui-database-host-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt"><?=_("Engine")?></div>
              <div class="bbn-light"
                   bbn-text="engines[currentData.engine]"/>
            </div>
            <div :class="['appui-database-host-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt"><?=_("Host")?></div>
              <div class="bbn-light"
                   bbn-text="currentData?.name"/>
            </div>
            <div bbn-if="currentData.ip"
                 :class="['appui-database-host-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt"><?=_("IP")?></div>
              <div class="bbn-light"
                   bbn-text="currentData.ip"/>
            </div>
            <div bbn-if="currentData?.user"
                 :class="['appui-database-host-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt"><?=_("User")?></div>
              <div class="bbn-light"
                   bbn-text="currentData.user"/>
            </div>
            <div bbn-if="currentData?.charset"
                 :class="['appui-database-host-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt"><?=_("Charset")?></div>
              <div class="bbn-light"
                   bbn-text="currentData.charset"/>
            </div>
            <div bbn-if="currentData?.collation"
                 :class="['appui-database-host-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt"><?=_("Collation")?></div>
              <div class="bbn-light"
                   bbn-text="currentData.collation"/>
            </div>
            <div bbn-if="currentData?.version"
                 :class="['appui-database-host-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt"><?=_("Version")?></div>
              <div class="bbn-light"
                   bbn-text="currentData.version"/>
            </div>
            <div :class="['appui-database-host-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt"><?=_("Size")?></div>
              <div class="bbn-light"
                   bbn-text="formatBytes(currentData.size)"/>
            </div>
          </div>
        </div>
                </div>
      </bbn-pane>
      <bbn-pane :resizable="true">
        <div class="bbn-overlay bbn-spadding bbn-radius">
          <div class="bbn-100 bbn-radius"
               style="box-shadow: 1px 1px 0.15rem var(--shadow-box)">
            <bbn-table ref="table"
                      @toggle="onTableToggle"
                      :source="currentData.dbs"
                      :selection="true"
                      :pageable="true"
                      :limit="50"
                      :info="true"
                      uid="name"
                      class="bbn-radius"
                      :scrollable="true"
                      style="border-color: var(--header-background)"
                      button-mode="menu">
              <bbns-column field="name"
                          label="<?= _('Database') ?>"
                          component="appui-database-db-cell"/>
              <bbns-column field="name"
                          label="<?= _('Size') ?>"
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
