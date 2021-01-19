<!-- HTML Document -->
<div class="bbn-overlay bbn-flex-height">
  <bbn-toolbar>
    <bbn-menu name="bbn-menu"
              orientation="horizontal"
              direction="bottom right"
              :source="[]"
              class="inline">
    </bbn-menu>
    <div class="bbn-block bbn-spadded">
      <span class="bbn-iblock bbn-left-space"><?=_("Host")?>: </span>&nbsp;
      <a :href="root + 'tabs/' + source.engine + '/' + source.host">
        <span class="bbn-b bbn-iblock bbn-right-space" v-text="source.host"></span>
      </a>
      <span class="bbn-iblock" v-if="source.is_real">#<?=_("Size")?>: </span>&nbsp;
      <span class="bbn-b bbn-iblock" v-text="source.size" v-if="source.is_real"></span>
    </div>
  </bbn-toolbar>
  <div class="bbn-flex-fill">
    <div class="bbn-100">
      <bbn-table :source="root + 'data/tables/' + source.engine + '/' + source.host + '/' + source.db"
                 :pageable="true"
                 :info="true"
                 ref="table"
                 :showable="true">
        <bbns-column field="name"
                     title="<?=_('Tables')?>"
                     cls="bbn-c"
                     :render="writeTable">
        </bbns-column>
        <bbns-column title="<a title='<?=_('Number of columns stored according to the database')?>'>#<?=_('Virtuals')?></a>"
                     field="num_columns"
                     type="number"
                     :width="80"
                     cls="bbn-c">
        </bbns-column>
        <bbns-column title="<a title='<?=_('Number of columns present in the database')?>'>#<?=_('Reals')?></a>"
                     field="num_real_columns"
                     type="number"
                     :width="80"
                     cls="bbn-c">
        </bbns-column>
        <bbns-column field="name"
                     title="actions"
                     :width="90"
                     cls="bbn-c"
                     :buttons="buttons">
        </bbns-column>
      </bbn-table>
    </div>
  </div>
</div>