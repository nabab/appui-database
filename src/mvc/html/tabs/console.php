<div class="bbn-flex-height"
>
  <bbn-code class="bbn-flex-fill"
            mode="sql"
            v-model="request"
  ></bbn-code>
  <div class="footer bbn-popup-footer k-button-group k-dialog-buttongroup k-dialog-button-layout-stretched bbn-flex-width">
    <button title=""
            class="k-button bbn-button bbn-events-component bbn-flex-fill bbn-primary"
            @click="execute">
      <i class="nf nf-fa-check_circle"></i> <span>Execute</span>
    </button>
  </div>
</div>