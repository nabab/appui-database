// Javascript Document
(() => {
  return {
    props: ['source'],
    created(){
      appui.register("database", this);
    }
  };
})();