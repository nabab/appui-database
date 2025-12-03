// Javascript Document

(() => {
  return {
    mixins: [bbn.cp.mixins.basic],
    props: {
      operation: {
        type: String
      },
      sub: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        shown: this.sub || (this.operation !== 'UPDATE')
      };
    },
    computed: {
      values() {
        const v = {};
        this.source.fields.map(f => v[f.name] = f.value);
        return v;
      },
      oldValues() {
        const v = {};
        this.source.fields.map(f => v[f.name] = f.old_value);
        return v;
      }
    },
    methods: {
      toggleShown() {
        this.shown = !this.shown;
      }
    }
  }
})();