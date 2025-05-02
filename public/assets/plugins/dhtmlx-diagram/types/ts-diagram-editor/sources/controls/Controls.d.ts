import { IEventSystem } from "../../../ts-common/events";
import { DataEvents, Diagram, DiagramEvents, IBaseCoords, IItemConfig, SelectionEvents } from "../../../ts-diagram";
import { DiagramEditorEvents, ICoords } from "../types";
import { BlockSelection } from "../BlockSelection";
import { Connect } from "./connect";
import { Id } from "../../../ts-common/types";
export declare function getLength(from: IItemConfig, to: IItemConfig): number;
export declare class Controls {
    connect: Connect;
    private _events;
    private _diagram;
    private _blockSelection;
    private _resize;
    private _diagramSize;
    private _isOrgType;
    constructor(events: IEventSystem<DataEvents | SelectionEvents | DiagramEvents | DiagramEditorEvents>, diagram: Diagram, blockSelection: BlockSelection);
    render(item: IItemConfig, size: any): any;
    setNearShape(shape: IItemConfig): void;
    toggleNearShape(shape: IItemConfig): void;
    getPoint(x: number, y: number): ICoords;
    onMove(event: MouseEvent, mov: ICoords, p: any): void;
    onUp(): void;
    connectOnUp(): void;
    protected _moveChilds(id: Id, mov: IBaseCoords): boolean;
    private _rotate;
    private _gripClick;
}
