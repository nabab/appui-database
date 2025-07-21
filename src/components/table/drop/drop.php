<bbn-form class="appui-database-table-drop bbn-lpadding bbn-c bbn-lg"
          :action="action"
          :source="formSource"
          submit-text="<?=_('Yes')?>"
          cancel-text="<?=_('No')?>"
          :prefilled="true"
          @success="onSuccess"
          @error="onError">
  <div bbn-text="message"/>
  <div class="bbn-light bbn-i">(<?=_('this action is irreversible')?>)</div>
  <div bbn-if="options"
        class="bbn-middle bbn-top-space">
    <bbn-switch bbn-model="formSource.options"
                class="bbn-right-sspace"
                :value="true"
                :novalue="false"/>
    <span><?=_('Delete from options')?></span>
  </div>
</bbn-form>
