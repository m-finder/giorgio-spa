// https://www.npmjs.com/package/mitt
import mitt, { Emitter } from 'mitt';

// 类型
// @ts-ignore
const emitter: Emitter<MittType> = mitt<MittType>();

// 导出
export default emitter;
