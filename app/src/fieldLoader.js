const blocks = BLOCK;
let components = {};
function getComponents() {
    let result = {};
    blocks.forEach(function (block){
        components[block.componentName] = block.filePath;
    })
    return result;
}
components = getComponents();
export  default components;