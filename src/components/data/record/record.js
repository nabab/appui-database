// Javascript Document

(() => {
  return {
    mixins: [bbn.cp.mixins.basic],
    data() {
      return {
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
  }
})();