import { VNode } from "../../../ts-common/dom";
import { Id } from "../../../ts-common/types";
import { BaseType, IBaseComponent, IBaseConfig, IBoxSize, ICoords, IItemConfig, ILinkConfig, IBaseGroupConfig } from "../../../ts-diagram";
export declare class BaseComponents implements IBaseComponent {
    config: any;
    id: Id;
    protected editorNode: VNode;
    protected editableProperty: {
        key: any;
        editableId: any;
    };
    constructor(config: any, params?: any);
    isFixed(): boolean;
    isEditable(): boolean;
    getBaseType(): BaseType;
    getBox(): IBoxSize;
    canResize(): boolean;
    render(): VNode;
    getEditorNode(): VNode;
    setEditorNode(editor: VNode, key?: string, editableId?: string): void;
    destructor(): void;
    getMetaInfo(): {
        [key: string]: string;
    }[];
    protected getCss(): string;
    protected setDefaults(config: IBaseConfig, defaults?: IBaseConfig): IBaseConfig;
    protected getCoords(config: IItemConfig | ILinkConfig | IBaseGroupConfig): ICoords;
}
