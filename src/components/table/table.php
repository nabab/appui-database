<!-- HTML Document -->
<div class="appui-database-table bbn-overlay">
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
    <appui-database-menu :source="toolbar"/>
    <bbn-splitter :orientation="orientation"
                  :resizable="false"
                  :collapsible="true"
                  class="bbn-flex-fill">
      <bbn-pane :resizable="true"
                :size="isHorizontal ? 250 : 'max-content'"
                :scrollable="true">
        <div class="bbn-smargin bbn-secondary-border bbn-radius"
             style="box-shadow: 1px 1px 0.15rem var(--shadow-box)">
          <div class="bbn-secondary bbn-c bbn-xspadding bbn-upper bbn-b"><?=_("Information")?></div>
          <div class="bbn-spadding bbn-c bbn-flex-column"
               style="gap: var(--space)">
            <div bbn-for="c in currentInfo"
                 :class="['appui-database-table-info-item', {'bbn-middle': !isHorizontal}]">
              <div class="bbn-upper bbn-secondary-text-alt bbn-b"
                   bbn-text="c.text"/>
              <div class="bbn-light"
                   bbn-text="c.value"/>
            </div>
          </div>
        </div>
      </bbn-pane>
      <bbn-pane :resizable="true">
        <div class="bbn-overlay bbn-spadding bbn-radius">
          <div class="bbn-100 bbn-radius"
               style="box-shadow: 1px 1px 0.15rem var(--shadow-box); border: var(--default-border-width) var(--default-border-style) var(--secondary-background)">
            <bbn-router :nav="true"
                        :autoload="false"
                        class="bbn-radius"
                        ref="router"
                        @route="onRouterRoute">
              <!-- <bbns-container component="appui-database-table-info"
                              url="home"
                              label="<?= _('Info') ?>"
                              :source="currentData"
                              icon="nf nf-oct-info"
                              :pinned="true"
                              :menu="false"/> -->
              <bbns-container component="appui-database-table-columns"
                              url="home"
                              label="<?= _('Columns') ?>"
                              :source="currentData"
                              icon="nf nf-fa-columns"
                              :pinned="true"
                              :menu="false"/>
              <bbns-container component="appui-database-table-keys"
                              url="keys"
                              icon="nf nf-md-key_chain_variant"
                              label="<?= _('Keys') ?>"
                              :source="currentData"
                              :pinned="true"
                              :menu="false"/>
              <bbns-container component="appui-database-table-constraints"
                              url="constraints"
                              icon="nf nf-md-relation_many_to_many"
                              label="<?= _('Constraints') ?>"
                              :source="currentData"
                              :pinned="true"
                              :menu="false"/>
              <bbns-container component="appui-database-table-data"
                              url="data"
                              icon="nf nf-fa-table_list"
                              label="<?= _('Data') ?>"
                              :source="currentData"
                              :pinned="true"
                              :menu="false"/>
              <bbns-container bbn-if="currentData.externals?.length"
                              component="appui-database-table-externals"
                              url="externals"
                              icon="nf nf-oct-link"
                              label="<?= _('Externals links') ?>"
                              :source="currentData"
                              :pinned="true"
                              :menu="false"/>
              <bbns-container component="appui-database-table-options"
                              url="options"
                              icon="nf nf-md-opera"
                              label="<?= _('Options') ?>"
                              :source="currentData"
                              :pinned="true"
                              :menu="false"/>
              <bbns-container component="appui-database-table-history"
                              url="history"
                              icon="nf nf-md-clock_start"
                              label="<?= _('History') ?>"
                              :source="currentData"
                              :pinned="true"
                              :menu="false"/>
            </bbn-router>
          </div>
        </div>
      </bbn-pane>
    </bbn-splitter>
  </div>
</div>

