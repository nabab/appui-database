<div class="bbn-overlay">
  <bbn-splitter :resizable="true">
    <bbn-pane :scrollable="false">
      <bbn-table :source="source.list"
                 :scrollable="true">
        <bbns-column field="query"/>
      </bbn-table>
    </bbn-pane>
    <bbn-pane :scrollable="true">
      <div class="bbn-w-100 bbn-padding bbn-content">
        <bbn-code/>
      </div>
    </bbn-pane>
  </bbn-splitter>
</div>
